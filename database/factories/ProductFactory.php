<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\ProductVariantSalesType;
use App\Models\SalesType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'moka_id_product' => rand(1000000, 9999999),
            'id_category' => Category::factory(),
            'name' => fake()->randomElement([
                'Lapis Legit Original',
                'Lapis Legit Keju',
                'Lapis Legit Coklat',
                'Lapis Legit Almond',
                'Lapis Legit Prune',
                'Lapis Surabaya Original',
                'Lapis Surabaya Keju',
            ]),
            'description' => fake()->randomElement([
                'Kue lapis legit dengan lapisan yang lembut dan aroma spesial yang menggugah selera.',
                'Kue lapis legit premium dengan topping keju melimpah yang nikmat.',
                'Kue lapis legit dengan coklat Belgia asli yang kaya rasa.',
                'Kue lapis legit dengan taburan almond panggang yang renyah.',
                'Kue lapis legit dengan buah prune asli yang manis dan lezat.',
                'Kue lapis Surabaya klasik dengan tekstur lembut dan moist.',
                'Kue lapis Surabaya dengan lapisan keju yang creamy.',
            ]),
            'weight' => fake()->randomElement([500, 750, 1000]),
            'width' => fake()->numberBetween(20, 25),
            'height' => fake()->numberBetween(5, 10),
            'length' => fake()->numberBetween(20, 25),
            'is_sales_type_price' => fake()->boolean(70), // 70% chance of true
            'is_active' => true,
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }

    /**
     * Create a Lapis Legit product with variants.
     *
     * @return static
     */
    public function lapisLegit()
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Lapis Legit Premium',
            'description' => 'Kue lapis legit premium dengan 18 lapisan yang lembut, dibuat dengan resep warisan keluarga dan bahan-bahan pilihan berkualitas tinggi.',
            'weight' => 800,
        ])->has(
            ProductVariant::factory()
                ->count(3)
                ->sequence(
                    ['name' => 'Lapis Legit 20cm', 'price' => 350000, 'stock' => 50, 'sku' => 'LL-20'],
                    ['name' => 'Lapis Legit 22cm', 'price' => 425000, 'stock' => 30, 'sku' => 'LL-22'],
                    ['name' => 'Lapis Legit 24cm', 'price' => 500000, 'stock' => 20, 'sku' => 'LL-24'],
                )
                ->afterCreating(function (ProductVariant $variant) {
                    // Add sales types for each variant
                    $salesTypes = SalesType::where('is_active', true)->limit(3)->get();

                    foreach ($salesTypes as $index => $salesType) {
                        ProductVariantSalesType::factory()->create([
                            'id_product_variant' => $variant->id,
                            'id_sales_type' => $salesType->id,
                            'price' => $variant->price + ($index * 25000),
                            'is_default' => $index === 0,
                        ]);
                    }
                }),
            'variants'
        )->has(
            ProductImage::factory()
                ->count(1)
                ->mainImage(),
            'images'
        );
    }

    /**
     * Create a Lapis Legit with specific flavor variants.
     *
     * @param array $flavors
     * @return static
     */
    public function withFlavors(array $flavors = ['Original', 'Keju', 'Coklat'])
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Lapis Legit ' . $flavors[0],
        ])->has(
            ProductVariant::factory()
                ->count(count($flavors))
                ->sequence(...collect($flavors)->map(fn ($flavor, $index) => [
                    'name' => "Lapis Legit {$flavor} - 20cm",
                    'price' => 300000 + ($index * 50000),
                    'stock' => fake()->numberBetween(20, 100),
                    'sku' => 'LL-' . strtoupper(substr($flavor, 0, 3)) . '-20',
                ])->toArray())
                ->afterCreating(function (ProductVariant $variant) {
                    ProductVariantSalesType::factory()->create([
                        'id_product_variant' => $variant->id,
                        'id_sales_type' => SalesType::firstOrCreate(
                            ['name' => 'Dine In'],
                            ['is_active' => true, 'created_by' => 1, 'updated_by' => 1]
                        )->id,
                        'is_default' => true,
                    ]);
                }),
            'variants'
        );
    }

    /**
     * Create product with image.
     *
     * @return static
     */
    public function withImage()
    {
        return $this->has(
            ProductImage::factory()->mainImage(),
            'images'
        );
    }

    /**
     * Create product with multiple images.
     *
     * @param int $count
     * @return static
     */
    public function withImages(int $count = 3)
    {
        return $this->has(
            ProductImage::factory()
                ->count($count)
                ->sequence(
                    ['is_main' => true],
                    ['is_main' => false],
                    ['is_main' => false],
                ),
            'images'
        );
    }

    /**
     * Create inactive product.
     *
     * @return static
     */
    public function inactive()
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
