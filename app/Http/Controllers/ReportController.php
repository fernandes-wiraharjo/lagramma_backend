<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ReportController extends Controller
{
    public function salesSummary()
    {
        return view('report-sales-summary');
    }

    public function getSalesSummary(Request $request)
    {
        $start = Carbon::parse($request->start_date)->startOfDay();
        $end = Carbon::parse($request->end_date)->endOfDay();

        $orders = Order::query()
            ->whereBetween('created_at', [$start, $end])
            ->where('status', 'delivered')
            ->get();

        $totalRevenue = $orders->sum('order_price');
        $totalOrders = $orders->count();
        $totalItems = $orders->sum('order_quantity');

        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return response()->json([
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'total_items' => $totalItems,
            'avg_order_value' => round($avgOrderValue),
        ]);
    }

    public function topProducts()
    {
        return view('report-top-products');
    }

    public function getTopProducts(Request $request)
    {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $columns = [
            1 => 'product_name',
            2 => 'units_sold',
            3 => 'revenue',
        ];

        // Base grouped query for data & filtered count
        $baseQuery = OrderDetail::selectRaw('
                order_details.product_name,
                order_details.product_variant_name,
                SUM(order_details.quantity) as units_sold,
                SUM(order_details.total_price) as revenue
            ')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.status', 'delivered')
            ->whereBetween('order_details.created_at', [$startDate, $endDate])
            ->groupBy('order_details.product_name', 'order_details.product_variant_name');

        // Clone for counting filtered records
        $filteredQuery = clone $baseQuery;

        // Apply search filter
        if ($search = $request->input('search.value')) {
            $baseQuery->havingRaw('
                (order_details.product_name LIKE ? OR order_details.product_variant_name LIKE ?)
            ', ["%{$search}%", "%{$search}%"]);

            $filteredQuery->havingRaw('
                (order_details.product_name LIKE ? OR order_details.product_variant_name LIKE ?)
            ', ["%{$search}%", "%{$search}%"]);
        }

        // Total (without search)
        $totalQuery = OrderDetail::selectRaw('order_details.product_name, order_details.product_variant_name')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.status', 'delivered')
            ->whereBetween('order_details.created_at', [$startDate, $endDate])
            ->groupBy('order_details.product_name', 'order_details.product_variant_name');

        $recordsTotal = DB::table(DB::raw("({$totalQuery->toSql()}) as total_sub"))
            ->mergeBindings($totalQuery->getQuery())
            ->count();

        // Filtered count
        $recordsFiltered = DB::table(DB::raw("({$filteredQuery->toSql()}) as filtered_sub"))
            ->mergeBindings($filteredQuery->getQuery())
            ->count();

        // Ordering
        $orderColIndex = $request->input('order.0.column', 2);
        $orderDir = $request->input('order.0.dir', 'desc');
        $orderColumn = $columns[$orderColIndex] ?? 'units_sold';
        $baseQuery->orderBy($orderColumn, $orderDir);

        // Pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $data = $baseQuery->skip($start)->take($length)->get();

        $results = $data->map(function ($item) {
            $name = $item->product_name;
            if (!empty($item->product_variant_name)) {
                $name .= ' - ' . $item->product_variant_name;
            }

            return [
                'name' => $name,
                'units_sold' => (int) $item->units_sold,
                'revenue' => (float) $item->revenue,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $results,
        ]);
    }
}
