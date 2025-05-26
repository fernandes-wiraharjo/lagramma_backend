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
            ->leftJoin('order_payments', 'orders.id', '=', 'order_payments.order_id')
            ->select(['orders.created_at as order_date', 'invoice_number', 'orders.status', 'order_price', 'payment_method']);

        if ($user->role->name === 'customer') {
            $query->where('orders.user_id', $user->id);
        }

         // Define sortable columns based on DataTables column index
         $sortableColumns = [
            0 => 'orders.created_at',
            2 => 'orders.status',
            3 => 'order_price',
            4 => 'payment_method'
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
                  ->orWhere('payment_method', 'like', $searchValue);
            });
        }

        // Get total records count (before filtering)
        if ($user->role->name === 'customer') {
            $totalRecords = Order::count()->where('orders.user_id', $user->id);
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
}
