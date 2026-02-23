<?php

namespace Database\Seeders;

/**
 * ⚠️  WARNING: DUMMY DATA SEEDER ⚠️
 *
 * This seeder creates DUMMY test data and will DELETE existing products.
 * Only use for development/testing purposes.
 *
 * Protected by:
 * - Environment check (only local/development/testing)
 * - Confirmation prompt
 * - Use --force flag to bypass (use with extreme caution!)
 *
 * Usage:
 *   php artisan db:seed --class=ProductDummySeeder
 *   php artisan db:seed --class=ProductDummySeeder --force  (bypass prompt)
 */

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\SalesType;
use Illuminate\Support\Facades\DB;

class ProductDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // SAFETY CHECK: Only allow in local/development environment unless --force is used
        $forceMode = in_array('--force', $_SERVER['argv']) || in_array('--force-dummy', $_SERVER['argv']);

        if (!app()->environment(['local', 'development', 'testing']) && !$forceMode) {
            $this->command->error('========================================');
            $this->command->error('  ProductDummySeeder DISABLED');
            $this->command->error('========================================');
            $this->command->warn('This seeder is only allowed in local/development environment.');
            $this->command->warn('Current environment: ' . app()->environment());
            $this->command->line('');
            $this->command->warn('To override, use: php artisan db:seed --class=ProductDummySeeder --force');
            return;
        }

        // Confirmation prompt (skipped if --force is used)
        if (!$forceMode && !$this->command->confirm(
            '⚠️  This will DELETE all existing products and create 50 dummy products. Continue?',
            false // default to false
        )) {
            $this->command->info('Seeding cancelled.');
            return;
        }

        if ($forceMode && !app()->environment(['local', 'development', 'testing'])) {
            $this->command->warn('Running in PRODUCTION with --force flag. Proceeding with caution...');
        }

        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing products and related data
        DB::table('product_variant_sales_types')->truncate();
        DB::table('product_variants')->truncate();
        DB::table('product_images')->truncate();
        DB::table('products')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ensure we have at least one category and sales types
        $bakeryCategory = Category::firstOrCreate(
            ['name' => 'Bakery'],
            [
                'moka_id_category' => 100001,
                'description' => 'Aneka roti dan kue segar dari oven kami.',
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );

        $lapisCategory = Category::firstOrCreate(
            ['name' => 'Lapis-Lapis'],
            [
                'moka_id_category' => 100002,
                'description' => 'Koleksi lapis legit dan lapis Surabaya premium.',
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );

        $cakeCategory = Category::firstOrCreate(
            ['name' => 'Cake'],
            [
                'moka_id_category' => 100003,
                'description' => 'Aneka cake premium untuk segala acara.',
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );

        // Ensure sales types exist
        $salesTypes = [
            ['name' => 'Dine In', 'moka_id' => 200001, 'is_active' => true],
            ['name' => 'Take Away', 'moka_id' => 200002, 'is_active' => true],
            ['name' => 'Delivery', 'moka_id' => 200003, 'is_active' => true],
            ['name' => 'GoFood', 'moka_id' => 200004, 'is_active' => true],
            ['name' => 'GrabFood', 'moka_id' => 200005, 'is_active' => true],
        ];

        foreach ($salesTypes as $salesType) {
            SalesType::firstOrCreate(
                ['name' => $salesType['name']],
                [
                    'moka_id_sales_type' => $salesType['moka_id'],
                    'is_active' => $salesType['is_active'],
                    'created_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('Seeding 50 bakery products with various combinations...');

        // 1. Lapis Legit Premium with 3 size variants (5 products)
        $this->command->info('Creating Lapis Legit Premium products...');
        Product::factory()->count(5)->lapisLegit()->create();

        // 2. Lapis Legit with different flavor combinations (10 products)
        $this->command->info('Creating Lapis Legit with flavor variants...');
        $flavorCombinations = [
            ['Original', 'Keju'],
            ['Original', 'Coklat'],
            ['Original', 'Almond'],
            ['Original', 'Prune'],
            ['Keju', 'Coklat'],
            ['Keju', 'Almond'],
            ['Coklat', 'Almond'],
            ['Original', 'Keju', 'Coklat'],
            ['Original', 'Keju', 'Almond'],
            ['Keju', 'Coklat', 'Prune'],
        ];

        foreach ($flavorCombinations as $index => $flavors) {
            Product::factory()
                ->withFlavors($flavors)
                ->withImages(rand(1, 3))
                ->create([
                    'id_category' => $lapisCategory->id,
                    'name' => "Lapis Legit {$flavors[0]} Variants",
                ]);
        }

        // 3. Standard bakery products with single variants (15 products)
        $this->command->info('Creating standard bakery products...');
        $standardProducts = [
            'Lapis Surabaya Original', 'Lapis Surabaya Keju', 'Lapis Surabaya Coklat',
            'Lapis Surabaya Almond', 'Brownies Fudge', 'Brownies Keju',
            'Brownies Almond', 'Blackforest', 'Chocolate Truffle Cake',
            'Red Velvet Cake', 'Cheese Cake', 'Pandan Cake',
            'Mochi Cake', 'Bolu Gulung', 'Muffin Assorted',
        ];

        foreach ($standardProducts as $index => $productName) {
            $isActive = $index < 12; // First 12 are active
            $hasImage = $index % 2 === 0; // Even index has image

            $factory = Product::factory()
                ->withImage()
                ->has(\App\Models\ProductVariant::factory()->count(rand(1, 2)), 'variants');

            if (!$isActive) {
                $factory = $factory->inactive();
            }

            $factory->create([
                'id_category' => rand(0, 1) ? $bakeryCategory->id : $cakeCategory->id,
                'name' => $productName,
                'is_active' => $isActive,
            ]);
        }

        // 4. Premium cakes with multiple variants and images (10 products)
        $this->command->info('Creating premium cakes with multiple variants...');
        $premiumCakes = [
            'Royal Wedding Cake', 'Anniversary Special', 'Birthday Deluxe',
            'Chocolate Ganache', 'Tiramisu Premium', 'Durian Cake Premium',
            'Mango Mousse Cake', 'Strawberry Shortcake', 'Opera Cake',
            'Creme Brulee Cake',
        ];

        foreach ($premiumCakes as $index => $cakeName) {
            Product::factory()
                ->withImages(rand(2, 4))
                ->has(
                    \App\Models\ProductVariant::factory()
                        ->count(rand(2, 4))
                        ->sequence(
                            fn($sequence) => [
                                'name' => "{$cakeName} - " . ['Small', 'Medium', 'Large', 'Extra Large'][$sequence->index % 4],
                                'price' => 250000 + ($sequence->index * 100000),
                                'sku' => strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $cakeName), 0, 5)) . '-' . ($sequence->index + 1),
                            ]
                        )
                        ->afterCreating(function (\App\Models\ProductVariant $variant) {
                            // Add sales types for each variant
                            $salesTypes = SalesType::where('is_active', true)->inRandomOrder()->limit(rand(2, 5))->get();

                            foreach ($salesTypes as $index => $salesType) {
                                \App\Models\ProductVariantSalesType::factory()->create([
                                    'id_product_variant' => $variant->id,
                                    'id_sales_type' => $salesType->id,
                                    'price' => $variant->price + ($index * 5000),
                                    'is_default' => $index === 0,
                                ]);
                            }
                        }),
                    'variants'
                )
                ->create([
                    'id_category' => $cakeCategory->id,
                    'name' => $cakeName,
                    'is_sales_type_price' => true,
                ]);
        }

        // 5. Simple products without variants (10 products)
        $this->command->info('Creating simple products...');
        $simpleProducts = [
            'Roti Tawar Sari Roti', 'Roti Coklat', 'Roti Keju',
            'Donat Original', 'Donat Coklat', 'Donat Glaze',
            'Croissant Butter', 'Croissant Almond', 'Baguette',
            'Ciabatta',
        ];

        foreach ($simpleProducts as $index => $productName) {
            $factory = Product::factory();

            // 60% have images
            if ($index % 5 < 3) {
                $factory = $factory->withImage();
            }

            $factory->create([
                'id_category' => $bakeryCategory->id,
                'name' => $productName,
                'is_sales_type_price' => $index % 2 === 0,
            ]);
        }

        $this->command->info('Successfully seeded 50 products with all combinations!');
    }
}
