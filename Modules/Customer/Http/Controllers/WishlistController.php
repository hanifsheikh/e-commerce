<?php

namespace Modules\Customer\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seller;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }
    public function save(Request $request)
    {
        if ($request->wishlist_data === null) {
            return;
        }
        $wishlist_items = [];
        $variant_ids = [];
        foreach ($request->wishlist_data as $item) {
            array_push($wishlist_items, json_decode($item, true));
        }
        foreach ($wishlist_items as $wishlist_item) {
            array_push($variant_ids, $wishlist_item['id']);
        }
        $product_seller_brand = DB::table('products')
            ->select('products.id as product_id', 'unit', 'brand_id', 'brand_name', 'seller_id', 'product_title', 'category_id', 'company_name as seller_company')
            ->join('sellers', 'products.seller_id', '=', 'sellers.id')
            ->join('brands', 'products.brand_id', '=', 'brands.id');

        $image = DB::table('product_images')
            ->select('product_variant_id', 'thumbnail AS image')
            ->where('position', 1);
        $items = DB::table('product_variants')
            ->select(
                'product_variants.seller_id',
                'product_variants.product_id',
                'brand_id',
                'category_id',
                'brand_name',
                'seller_company',
                'product_variants.product_title',
                'product_variants.product_variant_title',
                'image',
                'sku',
                'color',
                'color_code',
                'texture',
                'material',
                'size',
                'id as variant_id',
                'regular_price',
                'offer_price',
                'product_variant_url'
            )
            ->leftJoinSub($image, 'image', function ($join) {
                $join->on('product_variants.id', '=', 'image.product_variant_id');
            })
            ->leftJoinSub($product_seller_brand, 'product_seller', function ($join) {
                $join->on('product_variants.product_id', '=', 'product_seller.product_id');
            })
            ->whereIn('id', $variant_ids)
            ->get();

        foreach ($items as $item) {
            foreach ($wishlist_items as $wishlist_item) {
                if ($item->variant_id === $wishlist_item['id']) {
                    $item->quantity = $wishlist_item['quantity'];
                }
            }
            DB::table('product_wishlists')->where('customer_id', Auth::id())->where('product_variant_id', $item->variant_id)->delete();
            DB::table('product_wishlists')->insert([
                "product_variant_id" => $item->variant_id,
                "brand_id" => $item->brand_id,
                "quantity" => $item->quantity,
                "product_id" => $item->product_id,
                "seller_id" => $item->seller_id,
                "category_id" => $item->category_id,
                "customer_id" => Auth::id(),
                "brand_name" => $item->brand_name,
                "seller_company" => $item->seller_company,
                "product_title" => $item->product_title,
                "product_variant_title" => $item->product_variant_title,
                "sku" => $item->sku,
                "product_variant_url" => $item->product_variant_url,
                "color" => $item->color,
                "color_code" => $item->color_code,
                "texture" => $item->texture,
                "size" => $item->size,
                "material" => $item->material,
                "regular_price" => $item->regular_price,
                "offer_price" => $item->offer_price,
                "price" => $item->offer_price ? $item->offer_price  : $item->regular_price,
                "image" => $item->image,
                "created_at" => Carbon::now(),
            ]);
        }
        return view('wishlistsaved');
    }
    public function fetchWishListDetailsData()
    {
        $products = DB::table('product_wishlists')->select('product_variant_id', 'product_variant_title', 'product_title', 'size', 'color', 'texture', 'color_code', 'brand_name', 'image', 'product_variant_url', 'quantity')->where('customer_id', Auth::id())->get();
        return response()->json($products);
    }
    public function removeItem(Request $request)
    {
        $customerWishListExist = DB::table('product_wishlists')->where('customer_id', Auth::id())->where('product_variant_id', $request->product_variant_id)->first();
        if (!$customerWishListExist) {
            return response()->json(['status' => false, 'message' => 'Product is no longer available in your wishlist.']);
        }
        DB::table('product_wishlists')->where('customer_id', Auth::id())
            ->where('product_variant_id', $customerWishListExist->product_variant_id)->delete();
        return response()->json(['status' => true, 'message' => 'Product has been removed from your wishlist.']);
    }
}
