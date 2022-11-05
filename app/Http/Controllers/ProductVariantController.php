<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductVariantMeta;
use App\Models\ProductVariantService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductVariantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('HasPermission:product-view')->only(['fetchProductVariants', 'showProductVaraint']);
        $this->middleware('HasPermission:product-update')->only(['updateVariant']);
    }

    public function showProductVaraint($id)
    {
        $data = ProductVariant::where('id', $id)
            ->with(['product', 'images' => function ($query) {
                return $query->orderBy('position');
            }, 'services', 'meta', 'brand', 'seller'])
            ->first();
        return response()->json($data);
    }
    public function fetchProductVariants(Request $request)
    {
        $id = trim(preg_replace('/[[:^print:]]/', '', $request->id)); // Sanitize Input to Prevent SQL Injection
        $product = Product::where('id', $id)->with(['seller', 'brand'])->get();
        if (count($product)) {

            $image = DB::table('product_images')
                ->select('product_variant_id', 'thumbnail AS image')
                ->where('position', 1);

            $variants = DB::table('products')
                ->where('products.id', '=', $id)
                ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                ->leftJoinSub($image, 'image', function ($join) {
                    $join->on('product_variants.id', '=', 'image.product_variant_id');
                })
                ->select(
                    'product_variants.id as id',
                    'product_variants.sku as sku',
                    'product_variants.product_variant_title as product_variant_title',
                    'product_variants.color as color',
                    'product_variants.color_code as color_code',
                    'product_variants.texture',
                    'product_variants.size as size',
                    'image',
                    'product_variants.model_no as model_no',
                    'product_variants.regular_price as regular_price',
                    'product_variants.offer_price as offer_price',
                    'product_variants.stock_quantity as stock_quantity'
                )
                ->orderBy('id', 'DESC')
                ->get();
            return response()->json(['product_variants' => $variants, 'product' => $product, 'status' => true]);
        }
        return response()->json(['message' => 'Product Not found!', 'status' => false]);
    }
    public function updateVariant(Request $request)
    {
        ProductVariant::where('id', $request->id)->update([
            "product_variant_title" => $request->product_variant_title,
            "regular_price" => $request->regular_price,
            'offer_price' => $request->offer_price ? $request->offer_price : 0,
            'discount_in_percentage'  => $request->offer_price ? 100 -  (intval(($request->offer_price / $request->regular_price) * 100)) : 0,
            "size" => $request->size,
            "stock_quantity" => $request->stock_quantity,
            "color" => $request->color,
            "color_code" => $request->color_type === 1 ? $request->color_code : null,
            "texture" =>  $request->color_type === 2 ? $request->texture : null,
            "material" => $request->material,
            'weight' => $request->weight,
            'authenticity' => $request->authenticity,
            "shape" => $request->shape,
            "item_diameter" => $request->item_diameter,
            'delivery_time' => $request->delivery_time,
            'cash_on_delivery' => $request->cash_on_delivery,
            'model_no' => $request->model_no,
            'country_of_origin' => $request->country_of_origin,
        ]);
        ProductVariantMeta::where('product_variant_id', $request->id)->update([
            'about_the_item' => $request->meta['about_the_item'],
            'product_description' => $request->meta['product_description'],
            'keywords' => Str::lower($request->meta['keywords']),
            'product_components' => $request->meta['product_components'],
            'product_components_ratio_per_gram' => $request->meta['product_components_ratio_per_gram'],
        ]);
        ProductVariantService::where('product_variant_id', $request->id)->update([
            'delivery_charge' => $request->services['delivery_charge'] ? $request->services['delivery_charge'] : 0,
            'delivery_charge_outside' => $request->services['delivery_charge_outside'],
            'free_delivery_upto' => $request->services['free_delivery_upto'],
            'delivery_area' => $request->services['delivery_area'],
            'payment_first' => $request->services['payment_first'] == true,
            'payment_first_amount_in_percentage' => $request->services['payment_first'] ? 10 : null,
            'payment_first_amount_in_taka' => $request->services['payment_first'] ? (($request->offer_price != 0 || $request->offer_price != null) ? ($request->offer_price * 0.1) : ($request->regular_price * 0.1)) : null,
            'replacement_in_days' => $request->services['replacement_in_days'],
            'gurantee_in_months' => $request->services['gurantee_in_months'],
            'warranty_in_months' => $request->services['warranty_in_months'],
            'payment_first_delivery_charge' => $request->services['payment_first_delivery_charge'] == true
        ]);
        foreach ($request->images as $image) {
            ProductImage::where('product_variant_id', $request->id)->where('id', $image['id'])->update([
                'position' => $image['position'],
            ]);
        }
        return response()->json(['message' => 'Product variant information updated', 'status' => true]);
    }
}
