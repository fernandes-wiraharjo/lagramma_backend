<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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
            ->leftJoin('modifiers', 'modifiers.id', '=', 'product_modifiers.id_modifier')
            ->select([
                'products.id', 'products.moka_id_product', 'products.name as product_name', 'categories.name as category_name',
                'modifiers.name as modifier_name', 'products.is_active'
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

            $image = Image::make($file->getRealPath());

            // Resize if width > 800
            if ($image->width() > 800) {
                $image->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            $path = 'product-images/' . $filename;
            Storage::disk('public')->put($path, (string) $image->encode());

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
}
