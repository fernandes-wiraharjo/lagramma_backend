<?php

namespace Database\Factories;

use App\Models\ProductImage;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductImage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'image_path' => fake()->randomElement([
                'product-images/111_1764411533_ALMOND_N_CHEESE_BESAR.png',
                'product-images/81_1768377865_WhatsApp_Image_2026-01-13_at_15.37.40.jpeg',
                'product-images/87_1768378030_WhatsApp_Image_2026-01-13_at_15.37.40__3_.jpeg',
                'product-images/89_1768378071_WhatsApp_Image_2026-01-13_at_15.37.41__1_.jpeg',
            ]),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }

    /**
     * Indicate the image is the main image.
     *
     * @return static
     */
    public function mainImage()
    {
        return $this->state(fn (array $attributes) => [
            'is_main' => true,
        ]);
    }

    /**
     * Indicate the image is not the main image.
     *
     * @return static
     */
    public function notMainImage()
    {
        return $this->state(fn (array $attributes) => [
            'is_main' => false,
        ]);
    }
}
