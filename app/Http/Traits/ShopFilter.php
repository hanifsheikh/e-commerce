<?php

namespace App\Http\Traits;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ShopFilter
{
    public function filter(Request $request, $seller)
    {
        if (count($request->brands)) {
            return $this->filterByBrand($request, $seller);
        } else {
            return $this->resetFilterByBrand($seller);
        }
    }
    public function filterByBrand(Request $request, $seller)
    {
        $image = DB::table('product_images')
            ->select('product_variant_id', 'thumbnail AS image', 'position')
            ->orderBy('position')
            ->where('position', 1);
        $product_variants = DB::table('product_variants')
            ->select(
                'product_variants.id',
                'product_variants.regular_price',
                'product_variants.offer_price',
                'product_variants.product_id',
                'product_variants.stock_quantity',
                'product_variants.product_variant_url',
                'image',
            )
            ->leftJoinSub($image, 'image', function ($join) {
                $join->on('product_variants.id', '=', 'image.product_variant_id');
            });
        $products = DB::table('products')
            ->leftJoinSub($product_variants, 'product_variants', function ($join) {
                $join->on('products.id', '=', 'product_variants.product_id');
            })
            ->select(
                'products.*',
                DB::raw('MIN(product_variants.regular_price) as minimum_price'),
                DB::raw('MAX(product_variants.regular_price) as maximum_price'),
                DB::raw('MIN(product_variants.offer_price) as minimum_offer_price'),
                DB::raw('MAX(product_variants.offer_price) as maximum_offer_price'),
                DB::raw('SUM(product_variants.stock_quantity) as stock_quantity'),
                'product_variants.image',
                'product_variants.product_variant_url'
            )
            ->where('products.seller_id', $seller->id)
            ->whereIn('products.brand_id', $request->brands)
            ->groupBy('products.id')->get()->toArray();

        return $products;
    }
    public function resetFilterByBrand($seller)
    {
        $image = DB::table('product_images')
            ->select('product_variant_id', 'thumbnail AS image', 'position')
            ->orderBy('position')
            ->where('position', 1);
        $product_variants = DB::table('product_variants')
            ->select(
                'product_variants.id',
                'product_variants.regular_price',
                'product_variants.offer_price',
                'product_variants.product_id',
                'product_variants.stock_quantity',
                'product_variants.product_variant_url',
                'image',
            )
            ->leftJoinSub($image, 'image', function ($join) {
                $join->on('product_variants.id', '=', 'image.product_variant_id');
            });
        $products = DB::table('products')
            ->leftJoinSub($product_variants, 'product_variants', function ($join) {
                $join->on('products.id', '=', 'product_variants.product_id');
            })
            ->select(
                'products.*',
                DB::raw('MIN(product_variants.regular_price) as minimum_price'),
                DB::raw('MAX(product_variants.regular_price) as maximum_price'),
                DB::raw('MIN(product_variants.offer_price) as minimum_offer_price'),
                DB::raw('MAX(product_variants.offer_price) as maximum_offer_price'),
                DB::raw('SUM(product_variants.stock_quantity) as stock_quantity'),
                'product_variants.image',
                'product_variants.product_variant_url'
            )
            ->where('products.seller_id', $seller->id)
            ->groupBy('products.id')->get()->toArray();

        return $products;
    }
}
