<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

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
     * @return array
     */
    public function definition()
    {
        $category_ids = [1, 18, 19, 37, 38, 113, 114, 115, 156, 199, 200, 201];
        return [
            "product_title" => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            'category_id' => rand(1, 201),
            'category_parent_id' => $category_ids[rand(0, count($category_ids) - 1)],
            'category_second_level_id' => $category_ids[rand(0, count($category_ids) - 1)],
            'brand_id' => rand(1, 21),
            'unit' => "pcs",
            'seller_id' => rand(1, 10),
            'ratings' => rand(1, 5),
            'total_sales' => rand(1, 100),
        ];
    }
}
