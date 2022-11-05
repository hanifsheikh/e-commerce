<?php

namespace Database\Seeders;

use App\Models\ProductVariant;
use App\Models\ProductVariantMeta;
use App\Models\ProductVariantService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductVariantServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $areas = ['Dhaka', 'Chittagong', "Khulna"];
        DB::table('product_variant_services')->truncate();
        $product_variants = ProductVariant::all();
        foreach ($product_variants as $variant) {
            ProductVariantService::create([
                'product_variant_id' => $variant->id,
                'delivery_charge' => 35,
                'delivery_charge_outside' => 100,
                'free_delivery_upto' => rand(0, 2000),
                'delivery_area' =>  $areas[rand(0, 2)],
                'replacement_in_days' =>  14,
                'gurantee_in_months' => 5,
                'warranty_in_months' => 1
            ]);
            ProductVariantMeta::create([
                'product_variant_id' => $variant->id,
                'about_the_item' => '<ul><li>Easily store and access 2TB to content on the go with the Seagate Portable Drive, a USB external hard drive</li><li>Designed to work with Windows or Mac computers, this external hard drive makes backup a snap just drag and drop</li><li>To get set up, connect the portable hard drive to a computer for automatic recognition no software required</li><li>This USB drive provides plug and play simplicity with the included 18 inch USB 3.0 cable</li></ul>',
                'product_description' => '<p class="ql-align-justify ql-indent-8">      </p><h1 class="ql-align-center">Use AR to see them from every angle.</h1><p class="ql-align-center">Open this page in Safari on your iPhone or iPad.</p><p class="ql-align-center"><br></p><p class="ql-align-center"><br></p><p class="ql-align-center"><br></p><p class="ql-indent-8"><img src="https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/iphone-13-pro-ar-202109_GEO_US?wid=844&amp;hei=1122&amp;fmt=jpeg&amp;qlt=80&amp;.v=1630916354000" height="561" width="422"></p>',
                'keywords' => 'abc, def, ghi',
                'product_variant_embed_video_url' =>  null,
                'product_components' =>  14,
                'product_components_ratio_per_gram' => 5
            ]);
        }
    }
}
