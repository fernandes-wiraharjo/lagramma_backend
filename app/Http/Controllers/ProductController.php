<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\ProductDeactivateDate;
use App\Models\HamperSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    //product
    public function index()
    {
        return view('product');
    }

    public function get(Request $request)
    {
        $query = Product::query()
            ->leftJoin('categories', 'products.id_category', '=', 'categories.id')
            ->leftJoin('product_modifiers', 'products.id', '=', 'product_modifiers.id_product')
            ->leftJoin('modifiers', 'modifiers.id', '=', 'product_modifiers.id_modifier')
            ->select([
                'products.id', 'products.moka_id_product', 'products.name as product_name', 'categories.name as category_name',
                'modifiers.name as modifier_name', 'products.is_active', 'products.weight', 'products.width', 'products.height',
                'products.length'
            ]);

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

    public function update($id, Request $request)
    {
        $request->validate([
            'weight' => 'required|numeric|min:0',
            'width' => 'required|numeric|min:0',
            'height' => 'required|numeric|min:0',
            'length' => 'required|numeric|min:0',
        ]);

        $product = Product::findOrFail($id);

        $product->weight = $request->input('weight');
        $product->width = $request->input('width');
        $product->height = $request->input('height');
        $product->length = $request->input('length');
        $product->updated_by = auth()->id();
        $product->save();

        return response()->json(['success' => true, 'message' => 'Product updated successfully']);
    }


    //product variant
    public function indexVariant($idProduct)
    {
        $product = Product::findOrFail($idProduct);
        return view('product-variant', [
            'product' => $product
        ]);
    }

    public function getVariant($idProduct, Request $request)
    {
        $query = ProductVariant::query()
            ->leftJoin('products', 'products.id', '=', 'product_variants.id_product')
            ->leftJoin('product_variant_sales_types', 'product_variant_sales_types.id_product_variant', '=', 'product_variants.id')
            ->leftJoin('sales_types', 'sales_types.id', '=', 'product_variant_sales_types.id_sales_type')
            ->where('product_variants.id_product', $idProduct)
            ->where(function ($q) {
                $q->where('products.is_sales_type_price', 0)
                ->orWhere(function ($subQ) {
                    $subQ->where('products.is_sales_type_price', 1)
                        ->where('sales_types.name', 'Take Away');
                });
            })
            ->select([
                'product_variants.id',
                'product_variants.name',
                DB::raw("
                    CASE
                        WHEN products.is_sales_type_price = 0 THEN product_variants.price
                        WHEN products.is_sales_type_price = 1 AND sales_types.name = 'Take Away' THEN product_variant_sales_types.price
                        ELSE 0
                    END AS price
                "),
                'product_variants.stock',
                'product_variants.is_active',
            ]);

         // Define sortable columns based on DataTables column index
         $sortableColumns = [
            0 => 'name',
            1 => 'price',
            2 => 'stock',
            3 => 'is_active',
        ];

        // Retrieve sorting column index and direction from DataTables request
        $sortColumnIndex = $request->input('order.0.column', 0); // Default to first column
        $sortDirection = $request->input('order.0.dir', 'asc');  // Default to ascending

        // Determine the column name based on the column index
        $sortColumn = $sortableColumns[$sortColumnIndex] ?? 'name';

        // Apply search filtering
        if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = '%' . $request->search['value'] . '%';

            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', $searchValue);
            });
        }

        // Get total records count (before filtering)
        $totalRecords = ProductVariant::where('id_product', $idProduct)->count();

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

    public function toggleActiveVariant($id, Request $request)
    {
        $data = ProductVariant::findOrFail($id);
        $data->is_active = $request->input('is_active');
        $data->updated_by = auth()->id();
        $data->save();

        return response()->json(['success' => true]);
    }


    //product image
    public function indexImage($idProduct)
    {
        $product = Product::findOrFail($idProduct);
        return view('product-image', compact('product'));
    }

    public function storeImage(Request $request, $idProduct)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Max 2MB
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first('file')], 422);
        }

        $imageCount = ProductImage::where('product_id', $idProduct)->count();

        if ($imageCount >= 8) {
            return response()->json(['message' => 'Maximum 8 images allowed per product.'], 422);
        }

        $product = Product::findOrFail($idProduct);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $safeName = preg_replace('/[^A-Za-z0-9\-\_\.]/', '_', $originalName);
            $filename = $idProduct . '_' . time() . '_' . $safeName;

            $image = Image::make($file->getRealPath())->resize(600, 600, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $canvas = Image::canvas(600, 600, null);
            $canvas->insert($image, 'center');

            $path = 'product-images/' . $filename;
            Storage::disk('public')->put($path, (string) $canvas->encode('png'));

            $isFirst = !ProductImage::where('product_id', $idProduct)->where('is_main', true)->exists();

            $productImage = new ProductImage();
            $productImage->product_id = $idProduct;
            $productImage->image_path = $path;
            $productImage->is_main = $isFirst; // Set as main only if it's the first image
            $productImage->created_by = auth()->id();
            $productImage->save();

            return response()->json(['success' => true, 'image_id' => $productImage->id]);
        }

        return response()->json(['message' => 'No file uploaded.'], 400);
    }

    public function setMainImage($id)
    {
        $image = ProductImage::findOrFail($id);

        // Unset others
        ProductImage::where('product_id', $image->product_id)->update(['is_main' => false]);

        // Set current
        $image->is_main = true;
        $image->updated_by = auth()->id();
        $image->save();

        return response()->json(['success' => true]);
    }

    public function destroyImage($id)
    {
        $image = ProductImage::findOrFail($id);

        // Delete image file from storage
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Delete DB record
        $image->delete();

        return response()->json(['success' => true]);
    }


    //product deactivate by date
    public function indexDeactivateDate($idProduct)
    {
        $product = Product::findOrFail($idProduct);
        return view('product-deactivate-by-date', compact('product'));
    }

    public function getDeactivateDate($idProduct, Request $request)
    {
        $query = ProductDeactivateDate::query()
            ->select(['id', 'start_date', 'end_date'])
            ->where('product_id', $idProduct);

         // Define sortable columns based on DataTables column index
         $sortableColumns = [
            0 => 'start_date',
            1 => 'end_date'
        ];

        // Retrieve sorting column index and direction from DataTables request
        $sortColumnIndex = $request->input('order.0.column', 1); // Default to second column (name)
        $sortDirection = $request->input('order.0.dir', 'asc');  // Default to ascending

        // Determine the column name based on the column index
        $sortColumn = $sortableColumns[$sortColumnIndex] ?? 'start_date';

        // Get total records count (before filtering)
        $totalRecords = ProductDeactivateDate::where('product_id', $idProduct)->count();

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
            'recordsFiltered' => $totalRecords,
            'data' => $data,
        ]);
    }

    public function getDeactivateDateById($id)
    {
        $data = ProductDeactivateDate::find($id);

        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json($data);
    }

    public function storeDeactivateDate($idProduct, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'date_range' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 422);
            }

            $range = explode(' to ', $request->date_range);
            $start = Carbon::createFromFormat('d F Y H:i', trim($range[0]));
            $end = isset($range[1]) ? Carbon::createFromFormat('d F Y H:i', trim($range[1])) : $start;

            $data = ProductDeactivateDate::create([
                'product_id' => $idProduct,
                'start_date' => $start,
                'end_date' => $end,
                'created_by' => auth()->id(),
                'updated_at' => null
            ]);

            return response()->json(['success' => true, 'data' => $data], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create data. ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateDeactivateDate(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'date_range' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 422);
            }

            $data = ProductDeactivateDate::find($id);
            if (!$data) {
                return response()->json(['message' => 'Data not found'], 404);
            }

            $range = explode(' to ', $request->date_range);
            $start = Carbon::createFromFormat('d F Y H:i', trim($range[0]));
            $end = isset($range[1]) ? Carbon::createFromFormat('d F Y H:i', trim($range[1])) : $start;

            $data->update([
                'start_date' => $start,
                'end_date' => $end,
                'updated_by' => auth()->id()
            ]);

            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update data. ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyDeactivateDate($id)
    {
        try {
            $data = ProductDeactivateDate::findOrFail($id);
            $data->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete data. ' . $e->getMessage()
            ], 500);
        }
    }


    //hampers setting
    public function indexHamperSetting()
    {
        $hamperProducts = Product::whereHas('category', function ($query) {
            $query->where('name', 'Hampers');
        })->get();

        $items = ProductVariant::whereHas('product.category', function ($query) {
            $query->where('name', '!=', 'Hampers');
        })
        ->with('product')
        ->get()
        ->map(function ($variant) {
            $variant->combined_name = $variant->name
                ? $variant->product->name . ' - ' . $variant->name
                : $variant->product->name;
            return $variant;
        });

        return view('hampers-setting', compact('hamperProducts', 'items'));
    }

    public function getHamperSetting(Request $request)
    {
        $query = HamperSetting::query()
            ->join('products as hampers', 'hampers_settings.product_id', '=', 'hampers.id')
            ->leftJoin('hampers_setting_items', 'hampers_settings.id', '=', 'hampers_setting_items.hampers_setting_id')
            ->leftJoin('product_variants as items', 'hampers_setting_items.product_variant_id', '=', 'items.id')
            ->leftJoin('products as item_products', 'items.id_product', '=', 'item_products.id')
            ->groupBy('hampers_settings.id', 'hampers.name', 'hampers_settings.max_items')
            ->select([
                'hampers_settings.id',
                'hampers.name as hampers_name',
                'hampers_settings.max_items',
                DB::raw('GROUP_CONCAT(
                    CASE
                        WHEN items.name IS NULL OR items.name = ""
                        THEN item_products.name
                        ELSE CONCAT(item_products.name, " - ", items.name)
                    END
                    ORDER BY items.name SEPARATOR ", "
                ) as item_names')
            ]);

         // Define sortable columns based on DataTables column index
         $sortableColumns = [
            0 => 'hampers.name'
        ];

        // Retrieve sorting column index and direction from DataTables request
        $sortColumnIndex = $request->input('order.0.column', 0); // Default to first column
        $sortDirection = $request->input('order.0.dir', 'asc');  // Default to ascending

        // Determine the column name based on the column index
        $sortColumn = $sortableColumns[$sortColumnIndex] ?? 'hampers.name';

         // Apply search filtering
         if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = '%' . $request->search['value'] . '%';
            $query->havingRaw('hampers_name LIKE ? OR item_names LIKE ?', [$searchValue, $searchValue]);
        }

        // Get total records count (before filtering)
        $totalRecords = HamperSetting::count();

        // Get total filter records count (after filtering)
        $totalFiltered = $query->get()->count();

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

    public function editHamperSetting($id)
    {
        $hampers = HamperSetting::with('items')->findOrFail($id);

        return response()->json([
            'id' => $hampers->id,
            'product_id' => $hampers->product_id,
            'max_items' => $hampers->max_items,
            'allowed_items' => $hampers->items->pluck('id')
        ]);
    }

    public function storeHamperSetting(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'hampers' => 'required|exists:products,id',
                // 'max_items' => 'required|integer|min:1',
                'allowed_items' => 'required|array',
                'allowed_items.*' => 'exists:product_variants,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 422);
            }

            // Calculate max_items as the count of allowed_items
            $maxItems = count($request->allowed_items);

            $hampers = HamperSetting::create([
                'product_id' => $request->hampers,
                'max_items' => $maxItems,
                'created_by' => auth()->id()
            ]);

            $hampers->items()->sync($request->allowed_items);

            return response()->json(['success' => true], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create data. ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateHamperSetting(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                // 'max_items' => 'required|integer|min:1',
                'allowed_items' => 'required|array',
                'allowed_items.*' => 'exists:product_variants,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 422);
            }

            // Calculate max_items as the count of allowed_items
            $maxItems = count($request->allowed_items);

            $hampers = HamperSetting::findOrFail($id);
            $hampers->update([
                'max_items' => $maxItems,
                'updated_by' => auth()->id()
            ]);

            $hampers->items()->sync($request->allowed_items);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update data. ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyHamperSetting($id)
    {
        try {
            $hampers = HamperSetting::findOrFail($id);
            $hampers->items()->detach();
            $hampers->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete data. ' . $e->getMessage()
            ], 500);
        }
    }
}
