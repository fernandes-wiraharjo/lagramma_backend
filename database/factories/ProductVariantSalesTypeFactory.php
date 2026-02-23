<?php

namespace Database\Factories;

use App\Models\ProductVariantSalesType;
use App\Models\ProductVariant;
use App\Models\SalesType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariantSalesType>
 */
class ProductVariantSalesTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductVariantSalesType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $basePrice = fake()->numberBetween(50000, 500000);

        return [
            'id_product_variant' => ProductVariant::factory(),
            'id_sales_type' => SalesType::factory(),
            'price' => $basePrice,
            'is_default' => false,
            'is_active' => true,
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }

    /**
     * Indicate this is the default sales type.
     *
     * @return static
     */
    public function default()
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
        ]);
    }

    /**
     * Create inactive sales type.
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
     * Create with dine-in price adjustment.
     *
     * @return static
     */
    public function dineInPrice()
    {
        return $this->state(fn (array $attributes) => [
            'price' => $attributes['price'] ?? 100000 + 10000, // Add service charge
        ]);
    }

    /**
     * Create with delivery price adjustment.
     *
     * @return static
     */
    public function deliveryPrice()
    {
        return $this->state(fn (array $attributes) => [
            'price' => $attributes['price'] ?? 100000 + 15000, // Add delivery fee
        ]);
    }
}
