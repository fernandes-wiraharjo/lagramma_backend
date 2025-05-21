<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\OrderHampersItem;
use App\Models\OrderPayment;
use App\Models\ProductVariant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function mokaStockAdjustment($payload)
    {
        $baseUrl = env('MOKA_API_URL');
        $outletId = env('MOKA_OUTLET_ID');
        $token = getMokaToken();

        $response = Http::withToken($token)
            ->post($baseUrl . '/v1/outlets/' . $outletId . '/adjustment/items', $payload);

        if ($response->successful()) {
            Log::info('Adjust MOKA Item successfully.');
            return true;
        } else {
            $status = $response->status();

            // If token expired, refresh it and retry
            if ($status === 401) {
                $newToken = refreshMokaToken();
                if ($newToken) {
                    Log::info('Token refreshed. Retrying Adjust MOKA Item...');
                    return $this->mokaStockAdjustment($payload); // Retry
                }
            } else {
                Log::error('Adjust MOKA Item API failed', [
                    'status' => $status,
                    'body' => $response->body(),
                    'payload' => $payload
                ]);
                insertApiErrorLog('Adjust MOKA Item', "{$baseUrl}/v1/outlets/outlet_id/adjustment/items", 'POST', null, null, json_encode($payload), $status, $response->body());
                return false;
            }
        }
    }

    public function webhook(Request $request)
    {
        $webhookId = $request->header('webhook-id');

        // Check if this webhook-id has already been processed
        $existingWebhook = OrderPayment::where('webhook_id', $webhookId)->first();

        if ($existingWebhook) {
            // Already processed, ignore or return response
            Log::error('Duplicate xendit webhook ID received: ' . $webhookId);
            return response()->json(['message' => 'Duplicate webhook'], 400);
        }

        $data = $request->all();
        $user = auth()->user();
        $invoiceNo = $data['external_id'];
        $paymentStatus = $data['status'];
        $order = Order::with([
            'details.product',
            'delivery.address'  // eager load nested relation
        ])->where('invoice_number', $invoiceNo)->first();

        DB::beginTransaction();
        try {
            if (in_array($paymentStatus, ['PAID', 'SETTLED']) && $order->status == 'waiting for payment') {
                $orderDelivery = $order->delivery;
                $totalVariantsNeeded = [];
                $roOrderDetails = [];
                $mokaDetails = [];

                // Step 1: Collect stock
                foreach ($order->details as $item) {
                    //count needed qty for moka deduction
                    $key = $item->product_variant_id;
                    if (!isset($totalVariantsNeeded[$key])) {
                        $totalVariantsNeeded[$key] = 0;
                    }
                    $totalVariantsNeeded[$key] += $item->quantity;

                    if ($item->type === 'hampers') {
                        // Get hamper items and deduct their variant stock
                        $hamperItems = OrderHampersItem::where('order_detail_id', $item->id)->get();
                        foreach ($hamperItems as $hamperItem) {
                            //count needed qty for moka deduction
                            $key = $hamperItem->id;
                            if (!isset($totalVariantsNeeded[$key])) {
                                $totalVariantsNeeded[$key] = 0;
                            }
                            $totalVariantsNeeded[$key] += $hamperItem->quantity;
                        }
                    }

                    // store to order details for raja ongkir store order
                    $productPrice = $item->price;
                    $roOrderDetails[] = [
                        "product_name" => $item->product_name,
                        "product_variant_name" => $item->product_variant_name ?? '',
                        "product_price" => $productPrice,
                        "product_width" => intval($item?->product->width) ?? 1,
                        "product_height" => intval($item?->product->height) ?? 1,
                        "product_weight" => isset($item?->product->weight) ? $item?->product->weight * 1000 : 1000,
                        "product_length" => intval($item?->product->length) ?? 1,
                        "qty" => intval($item->quantity),
                        "subtotal" => $productPrice * $item->quantity
                    ];
                }

                // Step 2: update MOKA stock
                // Step 2.1: Validate total stock
                foreach ($totalVariantsNeeded as $variantId => $neededQty) {
                    $variant = ProductVariant::with('product')->find($variantId);
                    $productName = $variant->name
                        ? "{$variant->product->name} - {$variant->name}"
                        : $variant->product->name;
                    if (!$variant || $variant->stock < $neededQty) {
                        DB::rollBack();
                        Log::error('Error payment invoice no (view success) (1): ' . $invoiceNo . '. Product ' . $productName . ' only has ' . $variant->stock . ' left.');
                    }
                }

                // Deduct stock after validation passed
                foreach ($totalVariantsNeeded as $variantId => $neededQty) {
                    $variant = ProductVariant::find($variantId);
                    $variant->stock -= $neededQty;
                    $variant->updated_by = $user->id;
                    $variant->save();

                    //prepare moka data
                    if ($variant->track_stock == 1) {
                        $mokaDetails[$variantId] = [
                            'variant' => $variant,
                            'qty' => $neededQty
                        ];
                    }
                }

                $historyDetails = [];
                foreach ($mokaDetails as $entry) {
                    $variant = $entry['variant'];
                    $orderedQty = $entry['qty'];
                    $remainingStock = $variant->stock;

                    $historyDetails[] = [
                        'item_id' => $variant->product->moka_id_product,
                        'item_variant_id' => $variant->moka_id_product_variant,
                        'actual_stock' => $remainingStock
                    ];
                }

                if (count($historyDetails) > 0) {
                    $payload = [
                        'adjustment' => [
                            'note' => "ecomm:{$order->id}:{$invoiceNo}",
                            'history_details' => $historyDetails
                        ]
                    ];

                    $result = $this->mokaStockAdjustment($payload);
                    if (!$result) {
                        DB::rollBack();
                        Log::error('Error payment invoice no (view success) (2): ' . $invoiceNo . '. Terjadi kesalahan saat proses integrasi stock. Harap hubungi admin.' );
                    }
                } else {
                    DB::rollBack();
                    Log::error('Error payment invoice no (view success) (3): ' . $invoiceNo . '. Terjadi kesalahan saat proses integrasi stock. Tidak ada data yang dikirim ke moka.' );
                }

                // Step 3: Store order delivery to raja ongkir
                $komercePayload = [
                    "order_date" => $orderDelivery?->date,
                    "brand_name" => env('SHIPPER_BRAND_NAME'),
                    "shipper_name" => env('SHIPPER_NAME'),
                    "shipper_phone" => env('SHIPPER_PHONE'),
                    "shipper_destination_id" => intval(env('SHIPPER_REGION_ID')),
                    "shipper_address" => env('SHIPPER_ADDRESS'),
                    "origin_pin_point" => env('SHIPPER_LAT_LNG'),
                    "shipper_email" => env('SHIPPER_EMAIL'),
                    "receiver_name" => $user->name,
                    "receiver_phone" => normalizePhone($user->phone),
                    "receiver_destination_id" => intval($orderDelivery?->address?->region_id),
                    "receiver_address" => $orderDelivery?->address?->address,
                    "destination_pin_point" => $orderDelivery?->address?->latitude . ',' . $orderDelivery?->address?->longitude,
                    "shipping" => $orderDelivery?->shipping_name,
                    "shipping_type" => $orderDelivery?->shipping_type,
                    "payment_method" => "BANK TRANSFER",
                    "shipping_cost" => $orderDelivery?->shipping_cost,
                    "shipping_cashback" => $orderDelivery?->shipping_cashback,
                    "service_fee" => $orderDelivery?->service_fee,
                    "additional_cost" => 0,
                    "grand_total" => $orderDelivery?->grand_total,
                    "cod_value" => $orderDelivery?->grand_total,
                    "insurance_value" => 0,
                    "order_details" => $roOrderDetails
                ];
                $baseUrlKomerce = config('app.komerce_api_url');
                $komerceApiKey = config('app.komerce_api_key');

                // Step 3.1: Send order to Komerce
                $komerceResponse = Http::withHeaders([
                    'x-api-key' => $komerceApiKey
                ])->post("{$baseUrlKomerce}/order/api/v1/orders/store", $komercePayload);

                if (!$komerceResponse->successful() || $komerceResponse['meta']['code'] !== 201) {
                    Log::error('Error payment invoice no (view success) (4): ' . $invoiceNo . ', Failed to create Komerce order', [
                        'status' => $komerceResponse['meta']['code'],
                        'message' => $komerceResponse['meta']['message'],
                        'response' => $komerceResponse->body()
                    ]);
                    throw new \Exception('Failed to create Komerce order: ' . $komerceResponse['meta']['message']);
                }
                $komerceData = $komerceResponse['data'];

                // Step 3.2: Update order delivery data and status
                OrderDelivery::where('id', $orderDelivery->id)
                ->update([
                    'order_delivery_id' => $komerceData['order_id'],
                    'order_delivery_no' => $komerceData['order_no'],
                    'status' => 'submitted',
                    'updated_by' => $user->id,
                ]);

                // Step 4: Update order status
                Order::where('id', $order->id)
                ->update([
                    'status' => 'pending',
                    'updated_by' => $user->id,
                ]);

                // Step 5: Update order payment status
                OrderPayment::where('order_id', $order->id)
                ->update([
                    'status' => $paymentStatus,
                    'payment_id' => $data['payment_id'],
                    'payment_method' => $data['payment_method'],
                    'bank_code' => $data['bank_code'],
                    'payment_channel' => $data['payment_channel'],
                    'payment_destination' => $data['payment_destination'],
                    'paid_at' => Carbon::parse($data['paid_at'])->setTimezone('Asia/Jakarta'),
                    'webhook_id' => $webhookId,
                    'updated_by' => $user->id
                ]);
            } elseif ($in_array($paymentStatus, ['FAILED', 'EXPIRED'])) {
                // Update order status
                Order::where('id', $order->id)
                ->update([
                    'status' => 'payment failed',
                    'updated_by' => $user->id,
                ]);

                // Update order payment status
                OrderPayment::where('order_id', $order->id)
                ->update([
                    'status' => $paymentStatus,
                    'webhook_id' => $webhookId,
                    'updated_by' => $user->id
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error payment invoice no (webhook) (catch): ' . $invoiceNo . ', ' . $e->getMessage());
            return response()->json(['status' => 'failed'], 500);
        }

        return response()->json(['status' => 'success'], 200);
    }
}
