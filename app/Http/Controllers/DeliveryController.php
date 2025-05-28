<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDelivery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DeliveryController extends Controller
{
    public function webhook(Request $request)
    {
        $providedToken = $request->header('x-callback-token');
        $expectedToken = config('services.raja_ongkir.webhook_token_id');

        if ($providedToken !== $expectedToken) {
            Log::error('Unauthorized raja ongkir callback token received');
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = $request->all();
        $orderNo = $data['order_no'];
        $receiptNumber = $data['cnote'];
        $status = strtolower($data['status']);

        DB::beginTransaction();
        try {
            // Find the specific order delivery first
            $orderDelivery = OrderDelivery::where('order_delivery_no', $orderNo)
                ->where('receipt_number', $receiptNumber)
                ->first();

            if (!$orderDelivery) {
                throw new \Exception('Order delivery not found.');
            }

            // Update the order delivery status
            $orderDelivery->status = $status;
            $orderDelivery->save();

            // Update the related order status based on delivery status
            if ($status === 'sent') {
                Order::where('id', $orderDelivery->order_id)
                    ->update(['status' => 'picked up']);
            } elseif ($status === 'received') {
                Order::where('id', $orderDelivery->order_id)
                    ->update(['status' => 'delivered']);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error update delivery status (webhook): ' . $orderNo . ', ' . $e->getMessage());
            return response()->json(['status' => 'failed'], 500);
        }

        return response()->json(['status' => 'success'], 200);
    }
}
