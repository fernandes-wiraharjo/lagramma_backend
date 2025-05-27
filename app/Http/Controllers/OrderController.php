<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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

        return view('order-detail', compact('order'));
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
}
