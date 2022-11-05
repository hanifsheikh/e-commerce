<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $banners = [
            '1.jpg',
            '2.jpg',
            '3.jpg',
            '4.jpg'
        ];
        for ($i = 1; $i <= count($banners); $i++) {
            DB::table('banners')->insert([
                'image' => $banners[$i - 1],
                'position' => $i
            ]);
        }
        $recommendedProductInit = [
            [
                'id' => 1,
                'link' => null,
                'image' => 'recommended_product_1.jpg'
            ],
            [
                'id' => 2,
                'link' => null,
                'image' => 'recommended_product_2.jpg'
            ],
            [
                'id' => 3,
                'link' => null,
                'image' => 'recommended_product_3.jpg'
            ],
            [
                'id' => 4,
                'link' => null,
                'image' => 'recommended_product_4.jpg'
            ]
        ];
        DB::table('home_page_caches')->insert([
            'recommended_products' => json_encode($recommendedProductInit),
        ]);

        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            SellerSeeder::class,
            BrandSeeder::class,
            CustomerSeeder::class,
            SellerPaymentSeeder::class,
            OfferSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ProductVariantSeeder::class,
            ProductImageSeeder::class,
            ProductVariantServiceSeeder::class,
            CollectionSeeder::class,
        ]);
        $product_count = Product::get()->count();
        for ($i = 1; $i <= 5; $i++) {
            for ($j = 0; $j < 10; $j++) {
                DB::table('collection_products')->insert([
                    'collection_id' => $i,
                    'product_id' => rand(1, $product_count)
                ]);
            }
        }
    }
}
