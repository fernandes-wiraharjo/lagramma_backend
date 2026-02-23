<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductVariant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'moka_id_product_variant' => rand(1000000, 9999999),
            'id_product' => Product::factory(),
            'name' => fake()->randomElement([
                'Regular',
                'Large',
                'Premium',
                'Deluxe',
                'Small',
                'Medium',
                'Extra Large',
            ]),
            'price' => fake()->numberBetween(50000, 500000),
            'stock' => fake()->numberBetween(10, 200),
            'track_stock' => true,
            'position' => fake()->numberBetween(1, 10),
            'sku' => 'SKU-' . fake()->unique()->numerify('#####'),
            'is_active' => true,
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }

    /**
     * Create inactive variant.
     *
     * @return static
     */
    public function inactive()
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Create variant without stock tracking.
     *
     * @return static
     */
    public function noStockTracking()
    {
        return $this->state(fn (array $attributes) => [
            'track_stock' => false,
        ]);
    }

    /**
     * Create variant with unlimited stock.
     *
     * @return static
     */
    public function unlimitedStock()
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 9999,
            'track_stock' => false,
        ]);
    }
}
