<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantSalesType;
use App\Models\ProductModifier;
use App\Models\SalesType;
use App\Models\Modifier;
use App\Models\Category;

class SyncMokaProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-moka-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync master product from MOKA';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $baseUrl = env('MOKA_API_URL');
        $outletId = env('MOKA_OUTLET_ID');
        $token = getMokaToken();

        if (!$token) {
            Log::error('Failed to retrieve MOKA API token.');
            return;
        }

        $url = "{$baseUrl}/v1/outlets/{$outletId}/items?per_page=150";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->get($url);

        if ($response->successful()) {
            $responseData = $response->json();
            $listData = $responseData['data']['items'] ?? [];

            foreach ($listData as $data) {
                //sync products
                $product = $this->syncProduct($data);

                //sync product variants and product variant sales types
                if (!empty($data['item_variants'])) {
                    $this->syncProductVariants($product, $data['item_variants']);
                }

                //sycn product modifiers
                if (!empty($data['active_modifiers'])) {
                    $this->syncProductModifiers($product, $data['active_modifiers']);
                }
            }

            Log::info('Sync MOKA Product successfully.');
        } else {
            $status = $response->status();

            // If token expired, refresh it and retry
            if ($status === 401) {
                $newToken = refreshMokaToken();
                if ($newToken) {
                    Log::info('Token refreshed. Retrying Sync MOKA Product...');
                    $this->handle(); // Retry fetching data
                }
            } else {
                Log::error("Sync MOKA Product API Error: HTTP {$status}");
                insertApiErrorLog('Sync MOKA Product', "{$baseUrl}/v1/outlets/outlet_id/items", 'GET', null, null, null, $status, $response->body());
            }
        }
    }

    private function syncProduct($data)
    {
        // Find the category ID based on moka_id_category
        $category = Category::where('moka_id_category', $data['category_id'])->first();
        $categoryId = $category ? $category->id : null;

        // Insert or update product
        return Product::updateOrCreate(
            ['moka_id_product' => $data['id']],
            [
                'id_category' => $categoryId,
                'name'        => $data['name'],
                'description' => $data['description'] ?? null,
                'is_sales_type_price' => $data['is_sales_type_price'] ?? false,
                'updated_at'  => now()
            ]
        );
    }

    private function syncProductVariants($product, $variants)
    {
        foreach ($variants as $variantData) {
            $variant = ProductVariant::updateOrCreate(
                ['moka_id_product_variant' => $variantData['id']],
                [
                    'id_product' => $product->id,
                    'name'       => $variantData['name'],
                    'price'      => $variantData['price'] ?? null,
                    'stock'      => $variantData['in_stock'] ?? null,
                    'track_stock' => $variantData['track_stock'] ?? null,
                    'position'   => $variantData['position'] ?? 0,
                    'sku'   => $variantData['sku'] ?? null,
                    'updated_at' => now()
                ]
            );

            if (!empty($variantData['sales_type_items'])) {
                $this->syncProductVariantSalesTypes($variant, $variantData['sales_type_items']);
            }
        }
    }

    private function syncProductVariantSalesTypes($variant, $salesTypes)
    {
        foreach ($salesTypes as $salesType) {
            $salesTypeFound = SalesType::where('moka_id_sales_type', $salesType['sales_type_id'])->first();

            if ($salesTypeFound) {
                ProductVariantSalesType::updateOrCreate(
                    ['id_product_variant' => $variant->id, 'id_sales_type' => $salesTypeFound->id],
                    [
                        'price'  => $salesType['sales_type_price'] ?? null,
                        'is_default' => $salesType['is_default'] ?? null,
                        'updated_at'       => now()
                    ]
                );
            }
        }
    }

    private function syncProductModifiers($product, $modifiers)
    {
        foreach ($modifiers as $modifierData) {
            $modifier = Modifier::where('moka_id_modifier', $modifierData['id'])->first();

            if ($modifier) {
                ProductModifier::updateOrCreate(
                    ['id_product' => $product->id, 'id_modifier' => $modifier->id],
                    [
                        'updated_at' => now()
                    ]
                );
            }
        }
    }
}
