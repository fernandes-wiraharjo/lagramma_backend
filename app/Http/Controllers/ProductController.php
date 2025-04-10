<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class ProductController extends Controller
{
    public function index()
    {
        return view('product');
    }

    public function get(Request $request)
    {
        $query = Product::query()
            ->leftJoin('categories', 'products.id_category', '=', 'categories.id')
            ->leftJoin('product_modifiers', 'products.id', '=', 'product_modifiers.id_product')
            ->join('modifiers', 'modifiers.id', '=', 'product_modifiers.id_modifier')
            ->select([
                'products.id', 'products.moka_id_product', 'products.name as product_name', 'categories.name as category_name',
                'modifiers.name as modifier_name', 'products.is_active'
            ])
            ->where('categories.name', '!=', 'Hampers');

         // Define sortable columns based on DataTables column index
         $sortableColumns = [
            0 => 'moka_id_product',
            1 => 'products.name',
            2 => 'categories.name',
            3 => 'modifiers.name',
            4 => 'is_active',
        ];

        // Retrieve sorting column index and direction from DataTables request
        $sortColumnIndex = $request->input('order.0.column', 0); // Default to first column
        $sortDirection = $request->input('order.0.dir', 'asc');  // Default to ascending

        // Determine the column name based on the column index
        $sortColumn = $sortableColumns[$sortColumnIndex] ?? 'moka_id_product';

        // Apply search filtering
        if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = '%' . $request->search['value'] . '%';

            $query->where(function ($q) use ($searchValue) {
                $q->where('products.name', 'like', $searchValue)
                  ->orWhere('categories.name', 'like', $searchValue)
                  ->orWhere('modifiers.name', 'like', $searchValue);
            });
        }

        // Get total records count (before filtering)
        $totalRecords = Product::count();

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

    public function sync(Request $request)
    {
        try {
            Artisan::call('app:sync-moka-products');
            return response()->json(['success' => true, 'message' => 'Products synced successfully']);
        } catch (\Exception $e) {
            \Log::error('Moka Sync Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Sync failed. Check logs.'], 500);
        }
    }

    public function toggleActive($id, Request $request)
    {
        $data = Product::findOrFail($id);
        $data->is_active = $request->input('is_active');
        $data->updated_by = auth()->id();
        $data->save();

        return response()->json(['success' => true]);
    }
}
