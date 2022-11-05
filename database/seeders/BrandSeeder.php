<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\Brand::factory(20)->create();
        DB::table('brands')->truncate();
        $brands = [
            [
                'brand_name' => 'Fastrack',
                'brand_logo' => 'fastrack.jpg'
            ],
            [
                'brand_name' => 'Lakmé',
                'brand_logo' => 'lakme.jpg'
            ],
            [
                'brand_name' => "L'Oréal",
                'brand_logo' => 'loreal.jpg'
            ],

            [
                'brand_name' => 'Lamborgini',
                'brand_logo' => 'lamborghini.jpg'
            ],
            [
                'brand_name' => 'Rolex',
                'brand_logo' => 'rolex.jpg'
            ],
            [
                'brand_name' => 'Samsung',
                'brand_logo' => 'samsung.jpg'
            ],
            [
                'brand_name' => 'BMW',
                'brand_logo' => 'bmw-logo.jpg'
            ],
            [
                'brand_name' => 'Microsoft',
                'brand_logo' => 'microsoft.jpg'
            ],
            [
                'brand_name' => 'One Plus',
                'brand_logo' => 'one-plus.webp'
            ],
            [
                'brand_name' => 'Disney',
                'brand_logo' => 'disney.jpg'
            ],
            [
                'brand_name' => 'Pepsi',
                'brand_logo' => 'pepsi.jpg'
            ],
            [
                'brand_name' => 'Nike',
                'brand_logo' => 'nike.jpg'
            ],
            [
                'brand_name' => 'Apple',
                'brand_logo' => 'apple.jpg'
            ],
            [
                'brand_name' => 'McDonald’s',
                'brand_logo' => 'mcdonalds.jpg'
            ],
            [
                'brand_name' => 'Mercedes Benz',
                'brand_logo' => 'mercedes-benz.jpg'
            ],
            [
                'brand_name' => 'Coca Cola',
                'brand_logo' => 'coco-cola.jpg'
            ],
            [
                'brand_name' => 'UPS',
                'brand_logo' => 'ups.jpg'
            ],
            [
                'brand_name' => 'Chevrolet',
                'brand_logo' => 'chevrolet.jpg'
            ],
            [
                'brand_name' => 'Adidas',
                'brand_logo' => 'adidas.jpg'
            ],
            [
                'brand_name' => 'Puma',
                'brand_logo' => 'puma.jpg'
            ],

            [
                'brand_name' => 'Gucci',
                'brand_logo' => 'gucci.jpg'
            ],
            [
                'brand_name' => 'Bata',
                'brand_logo' => 'bata.jpg'
            ],
            [
                'brand_name' => 'Apex',
                'brand_logo' => 'apex.jpg'
            ],
            [
                'brand_name' => 'Lotto',
                'brand_logo' => 'lotto.jpg'
            ],
            [
                'brand_name' => 'Olay',
                'brand_logo' => 'olay.jpg'
            ],

        ];
        foreach ($brands as $brand) {
            Brand::create([
                'brand_name' => $brand['brand_name'],
                'slug' => Str::slug($brand['brand_name']),
                'brand_logo' => $brand['brand_logo']
            ]);
        }
    }
}
