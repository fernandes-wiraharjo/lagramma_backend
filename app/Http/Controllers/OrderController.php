<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\Notification;
use App\Mail\PickupRequestedMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Picqer\Barcode\BarcodeGeneratorPNG;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = Order::query();

        if ($user->role->name === 'customer') {
            $query->where('user_id', $user->id);
        }

        $statuses = $query->whereIn('status', [
            'waiting for payment',
            'pending',
            'packed',
            'request picked up',
            'picked up',
            'delivered',
            'payment failed',
        ])
        ->select('status', DB::raw('count(*) as total'))
        ->groupBy('status')
        ->pluck('total', 'status');

        return view('order', [
            'waiting'   => $statuses['waiting for payment'] ?? 0,
            'pending'   => $statuses['pending'] ?? 0,
            'packed'   => $statuses['packed'] ?? 0,
            'requestPickedUp'   => $statuses['request picked up'] ?? 0,
            'pickedUp'  => $statuses['picked up'] ?? 0,
            'delivered' => $statuses['delivered'] ?? 0,
            'cancelled' => $statuses['payment failed'] ?? 0,
        ]);
    }

    public function get(Request $request)
    {
        $user = auth()->user();

        $query = Order::query()
            ->leftJoin('users', 'users.id', 'orders.user_id')
            ->leftJoin('order_payments', 'orders.id', '=', 'order_payments.order_id')
            ->select(['orders.id', 'orders.created_at as order_date', 'invoice_number', 'orders.status', 'order_price',
                'payment_method', 'users.name as user_name']);

        if ($user->role->name === 'customer') {
            $query->where('orders.user_id', $user->id);
        }

        if ($request->start_date || $request->end_date) {
            if (!$request->end_date) {
                $request->end_date = $request->start_date;
            }
            $start = Carbon::createFromFormat('d M, Y', $request->start_date)->startOfDay();
            $end = Carbon::createFromFormat('d M, Y', $request->end_date)->endOfDay();

            $query->whereBetween('orders.created_at', [$start, $end]);
        }

         // Define sortable columns based on DataTables column index
         $sortableColumns = [
            0 => 'orders.created_at',
            1 => 'users.name',
            3 => 'orders.status',
            4 => 'order_price',
            5 => 'payment_method'
        ];

        // Retrieve sorting column index and direction from DataTables request
        $sortColumnIndex = $request->input('order.0.column', 0); // Default to first column
        $sortDirection = $request->input('order.0.dir', 'desc');  // Default to descending

        // Determine the column name based on the column index
        $sortColumn = $sortableColumns[$sortColumnIndex] ?? 'orders.created_at';

        // Apply search filtering
        if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = '%' . $request->search['value'] . '%';

            $query->where(function ($q) use ($searchValue) {
                $q->where('invoice_number', 'like', $searchValue)
                  ->orWhere('orders.status', 'like', $searchValue)
                  ->orWhere('payment_method', 'like', $searchValue)
                  ->orWhere('users.name', 'like', $searchValue);
            });
        }

        // Get total records count (before filtering)
        if ($user->role->name === 'customer') {
            $totalRecords = Order::where('orders.user_id', $user->id)->count();
        } else {
            $totalRecords = Order::count();
        }

        // Get total filter records count (after filtering)
        $totalFiltered = $query->count();

        // Apply sorting and pagination
        $data = $query
            ->orderBy($sortColumn, $sortDirection)
            ->offset($request->input('start', 0))
            ->limit($request->input('length', 10))
            ->get();

        // Prepare response data
        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    public function getDetail($invoice_number)
    {
        $order = Order::with([
            'user',
            'delivery.address',
            'details.product.mainImage',
            'details.modifiers',
            'payment'
        ])
        ->where('invoice_number', $invoice_number)
        ->first();

        if (!$order) {
            abort(404, 'Order not found');
        }

        $trackingFounded = false;
        $trackingData = [];
        if ($order->delivery->receipt_number != "") {
            $shippingName = $order->delivery->shipping_name;
            $airwayBill = $order->delivery->receipt_number;
            $apiKey = config('app.komerce_api_key');
            $baseUrlKomerce = config('app.komerce_api_url');

            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
            ])->get("{$baseUrlKomerce}/order/api/v1/orders/history-airway-bill", [
                'shipping' => $shippingName,
                'airway_bill' => $airwayBill
            ]);

            $result = $response->json();

            if ($response->successful() && $result['meta']['status'] === 'success') {
                $trackingFounded = true;
                $trackingData = $result['data'];
            } else {
                $trackingFounded = false;
            }
        }

        return view('order-detail', compact('order', 'trackingFounded', 'trackingData'));
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'status' => 'required',
        ]);

        $order = Order::findOrFail($id);

        $order->status = $request->input('status');
        $order->updated_by = auth()->id();
        $order->save();

        return response()->json(['success' => true, 'message' => 'Order updated successfully']);
    }

    public function requestPickup(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $order = Order::with(['details.product', 'delivery', 'user'])->findOrFail($id);

            // Step 1: Calculate pickup time
            $pickupDateTime = now()->addMinutes(95);
            $pickupDate = $pickupDateTime->format('Y-m-d');
            $pickupTime = $pickupDateTime->format('H:i');
            $baseUrlKomerce = config('app.komerce_api_url');
            $komerceApiKey = config('app.komerce_api_key');
            $orderDeliveryNo = $order->delivery->order_delivery_no;
            $invoiceNumber = $order->invoice_number;

            // Step 2: Calculate total weight
            $totalWeight = $order->details->sum(function ($detail) {
                return optional($detail->product)->weight * $detail->qty;
            });

            // Step 3: Determine vehicle type
            $vehicle = 'Motor';
            if ($totalWeight >= 10) $vehicle = 'Truck';
            elseif ($totalWeight > 5) $vehicle = 'Mobil';

            $apiRequestBody = [
                'pickup_date' => $pickupDate,
                'pickup_time' => $pickupTime,
                'pickup_vehicle' => $vehicle,
                'orders' => [
                    ['order_no' => $orderDeliveryNo]
                ]
            ];
            $apiRequestUrl = "{$baseUrlKomerce}/order/api/v1/pickup/request";
            $apiHeaders = ['x-api-key' => $komerceApiKey];

            // Step 4: Call external pickup API
            $response = Http::withHeaders($apiHeaders)->post($apiRequestUrl, $apiRequestBody);

            // Log both success and failed for now
            insertApiErrorLog(
                'Pickup Request',
                $apiRequestUrl,
                'POST',
                json_encode([]),
                json_encode([]),
                json_encode($apiRequestBody),
                $response->status(),
                $response->body()
            );

            if ($response->status() !== 201 || empty($response['data'][0]['awb'])) {
                return response()->json([
                    'meta' => [
                        'message' => $response['meta']['message'] ?? 'Pickup request failed',
                        'code' => $response->status(),
                        'status' => 'error',
                    ],
                    'data' => null
                ], $response->status());
            }

            $awb = $response['data'][0]['awb'];

            // Step 5: Update order and delivery
            $order->update(['status' => 'request picked up']);

            OrderDelivery::where('order_id', $id)
                ->update(['receipt_number' => $awb]);

            // Step 6: Notification + Email
            Notification::create([
                'user_id' => $order->user_id,
                'title' => 'Pickup Requested',
                'type' => 'info_resi',
                'message' => 'Order #' . $invoiceNumber . ' has been scheduled for pickup. Resi no: ' . $awb,
                'link' => url("/orders/{$invoiceNumber}/detail#track-order"),
                'is_read' => false,
                'created_by' => auth()->id(),
                'updated_at' => null
            ]);

            Mail::to($order->user->email)->send(new PickupRequestedMail($order, $awb));

            DB::commit();

            return response()->json([
                'meta' => [
                    'message' => 'Pickup requested successfully. Pickup will be approximately at ' . now()->addMinutes(90)->format('H:i') . '.',
                    'code' => 201,
                    'status' => 'success'
                ],
                'data' => [
                    'order_no' => $orderDeliveryNo,
                    'awb' => $awb
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'meta' => [
                    'message' => $e->getMessage(),
                    'code' => 500,
                    'status' => 'error',
                ],
                'data' => null
            ], 500);
        }
    }

    public function printInvoiceBarcode(Request $request, $invoiceNo)
    {
        try {
            // Generate barcode image
            $generator = new BarcodeGeneratorPNG();
            $barcodeBinary = $generator->getBarcode($invoiceNo, $generator::TYPE_CODE_128, 2, 60);

            // Convert to base64
            $barcodeBase64 = base64_encode($barcodeBinary);
            $barcodeSrc = 'data:image/png;base64,' . $barcodeBase64;

            // Return HTML directly with embedded base64 image
            $html = view('print-invoice-barcode', [
                'invoiceNo' => $invoiceNo,
                'barcodeUrl' => $barcodeSrc
            ])->render();

            return response()->json([
                'meta' => [
                    'message' => 'Barcode generated successfully.',
                    'html' => $html
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'meta' => [
                    'message' => 'Failed to generate barcode: ' . $e->getMessage()
                ]
            ], 500);
        }
    }
}
