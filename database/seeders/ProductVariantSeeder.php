<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_variants')->truncate();

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
        $materials = [
            'Cast Iron',
            'Plastic',
            'Steel',
            'Fabric',
            'Glass',
            'Aluminium'
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

        $products = Product::all();


        foreach ($products as $product) {

            $upperCasedProductTitle =  Str::upper(preg_replace('/[^A-Za-z0-9\-]/', '', $product->product_title));
            $upperCasedProductCategory =  Str::upper(preg_replace('/[^A-Za-z0-9\-]/', '', $product->category->category_name));


            for ($i = 0; $i < rand(1, 2); $i++) {
                $regular_price =   rand(500, 1000);
                $offer_price =  $regular_price - 150;
                $color = rand(0, count($colors) - 1);
                $size =  rand(0, count($sizes) - 1);
                $material =  rand(0, count($materials) - 1);
                $sku = substr($upperCasedProductTitle, 0, 3) .
                    rand(1, 100) .
                    Str::upper(Str::random(5)) .
                    substr($upperCasedProductCategory, 0, 3);
                ProductVariant::create([
                    'product_id' => $product->id,
                    'seller_id' => $product->seller_id,
                    'product_title' => $product->product_title,
                    'product_variant_title' => $colors[$color]['name'] . ', ' . $sizes[$size] . ', ' . $materials[$material],
                    'regular_price' => $regular_price,
                    'offer_price' => $offer_price,
                    // 'stock_quantity' => 0,
                    'stock_quantity' => rand(20, 100),
                    'discount_in_percentage' => 100 - intval(($offer_price / $regular_price) * 100),
                    'color' => $colors[$color]['name'],
                    'color_code' => $colors[$color]['code'],
                    'size' => $sizes[$size],
                    'material' => $materials[$material],
                    "product_variant_url" => Str::slug($product->product_title, '-') . '/' . $sku,
                    'model_no' => Str::upper(Str::random(5)),
                    'sku' => $sku
                ]);
            }
        }
    }
}
