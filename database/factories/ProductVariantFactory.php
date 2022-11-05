<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
     * @return array
     */
    public function definition()
    {
        $colors = [
            [
                "code" => "#FFFFFF",
                "name" => 'White'
            ],
            [
                "code" => "#C0C0C0",
                "name" => 'Silver'
            ],
            [
                "code" => "#808080",
                "name" => 'Gray'
            ],
            [
                "code" => "#000000",
                "name" => 'Black'
            ],
            [
                "code" => "#FF0000",
                "name" => 'Red'
            ],
            [
                "code" => "#800000",
                "name" => 'Maroon'
            ],
            [
                "code" => "#FFFF00",
                "name" => 'Yellow'
            ],
            [
                "code" => "#808000",
                "name" => 'Olive'
            ],
            [
                "code" => "#00FF00",
                "name" => 'Lime'
            ],
            [
                "code" => "#008000",
                "name" => 'Green'
            ],
            [
                "code" => "#00FFFF",
                "name" => 'Aqua'
            ],
            [
                "code" => "#008080",
                "name" => 'Teal'
            ],
            [
                "code" => "#0000FF",
                "name" => 'Blue'
            ],
            [
                "code" => "#000080",
                "name" => 'Navy'
            ],
            [
                "code" => "#FF00FF",
                "name" => 'Fuchsia'
            ],
            [
                "code" => "#800080",
                "name" => 'Purple'
            ],
        ];
        $sizes = [
            'Small',
            'Medium',
            'Large',
            'XL',
            '10.7"',
            '18.3"',
            '2XL',
            '3XL',
        ];
        $color = rand(0, count($colors) - 1);
        $size =  rand(0, count($sizes) - 1);



        $product_title = $this->faker->name();
        $product_category = "Watches & Accessories";


        $upperCasedProductTitle =  Str::upper(preg_replace('/[^A-Za-z0-9\-]/', '', $product_title));
        $upperCasedProductCategory =  Str::upper(preg_replace('/[^A-Za-z0-9\-]/', '', $product_category));
        return [
            'product_id' => rand(1, 20),
            'product_title' => $product_title,
            'product_variant_title' => $this->faker->name(),
            'regular_price' => rand(500, 1000),
            'offer_price' => rand(0, 500),
            'stock_quantity' => rand(20, 100),
            'color' => $colors[$color]['name'],
            'color_code' => $colors[$color]['code'],
            'size' => $sizes[$size],
            'model_no' => Str::upper(Str::random(5)),
            'sku' =>
            substr($upperCasedProductTitle, 0, 3) .

                rand(1, 100) .

                Str::upper(Str::random(5)) .

                Str::upper($colors[$color]['name']) .

                substr($upperCasedProductCategory, 0, 3)

        ];
    }
}
