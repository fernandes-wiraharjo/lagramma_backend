<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'moka_id_category' => rand(1000000, 9999999),
            'name' => fake()->randomElement([
                'Kue Basah',
                'Kue Kering',
                'Roti Manis',
                'Roti Tawar',
                'Donat',
                'Cake',
                'Lapis-Lapis',
                'Pai & Tart',
            ]),
            'description' => fake()->sentence(),
            'is_active' => true,
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }

    /**
     * Create a bakery category.
     *
     * @return static
     */
    public function bakery()
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Bakery',
            'description' => 'Aneka roti dan kue segar dari oven kami.',
        ]);
    }

    /**
     * Create a lapis category.
     *
     * @return static
     */
    public function lapis()
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Lapis-Lapis',
            'description' => 'Koleksi lapis legit dan lapis Surabaya premium.',
        ]);
    }

    /**
     * Create inactive category.
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
