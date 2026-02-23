<?php

namespace Database\Factories;

use App\Models\SalesType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SalesType>
 */
class SalesTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SalesType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'moka_id_sales_type' => rand(1000000, 9999999),
            'name' => fake()->randomElement([
                'Dine In',
                'Take Away',
                'Delivery',
                'GoFood',
                'GrabFood',
                'ShopeeFood',
            ]),
            'is_active' => true,
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }

    /**
     * Create a Dine In sales type.
     *
     * @return static
     */
    public function dineIn()
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Dine In',
        ]);
    }

    /**
     * Create a Take Away sales type.
     *
     * @return static
     */
    public function takeAway()
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Take Away',
        ]);
    }

    /**
     * Create a Delivery sales type.
     *
     * @return static
     */
    public function delivery()
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Delivery',
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
}
