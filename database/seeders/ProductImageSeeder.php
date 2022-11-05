<?php

namespace Database\Seeders;

use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_images')->truncate();
        $product_variants = ProductVariant::all();
        $imageArray = [
            "1.jpg",
            "10.jpg",
            "11.jpg",
            "12.jpg",
            "14.jpg",
            "15.jpg",
            "17.jpg",
            "18.jpg",
            "19.jpg",
            "2.jpg",
            "21.jpg",
            "22.jpg",
            "23.jpg",
            "24.jpg",
            "25.webp",
            "27.jpg",
            "28.jpg",
            "29.jpg",
            "3.jpg",
            "30.jpg",
            "32.jpg",
            "33.jpg",
            "34.jpg",
            "35.jpg",
            "36.webp",
            "37.webp",
            "38.webp",
            "39.jpg",
            "4.webp",
            "40.webp",
            "5.jpg",
            "6.jpg",
            "7.jpg",
            "8.jpg",
            "9.jpg"
        ];
        foreach ($product_variants as $variant) {
            for ($i = 1; $i <= 3; $i++) {
                $image = $imageArray[rand(0, count($imageArray) - 1)];
                ProductImage::create([
                    'product_id' => $variant->product_id,
                    'product_variant_id' => $variant->id,
                    'image_url' => $image,
                    'thumbnail' => $image,
                    'position' => $i,
                ]);
            }
        }
    }
}
