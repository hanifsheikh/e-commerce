<?php

namespace Modules\Seller\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantMeta;
use App\Models\ProductVariantService;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:seller');
    }

    public function fetchProducts()
    {
        if (!Auth::user()) {
            return abort(401);
        }
        $image = DB::table('product_images')
            ->select('product_variant_id', 'thumbnail AS image', 'position')
            ->where('position', 1);
        $product_variants = DB::table('product_variants')
            ->select(
                'product_variants.id',
                'product_variants.regular_price',
                'product_variants.product_id',
                'product_variants.stock_quantity',
                'image'
            )
            ->leftJoinSub($image, 'image', function ($join) {
                $join->on('product_variants.id', '=', 'image.product_variant_id');
            });
        $products = DB::table('products')
            ->where('seller_id', Auth::id())
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoinSub($product_variants, 'product_variants', function ($join) {
                $join->on('products.id', '=', 'product_variants.product_id');
            })
            ->select(
                'products.*',
                DB::raw('MIN(product_variants.regular_price) as minimum_price'),
                DB::raw('MAX(product_variants.regular_price) as maximum_price'),
                DB::raw('SUM(product_variants.stock_quantity) as stock_quantity'),
                DB::raw('COUNT(product_id) as variant_count'),
                'product_variants.image',
                'categories.category_name as category_name'
            )
            ->groupBy('products.id')
            ->orderByRaw('products.id DESC')
            ->paginate(100);

        return response()->json($products);
    }
    public function fetchShopProducts()
    {
        $seller = Seller::where('id', Auth::id())->first();
        if (!$seller) {
            return abort(401);
        }
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
                DB::raw('COUNT(product_id) as variant_count'),
                'product_variants.image',
                'product_variants.product_variant_url'
            )
            ->where('products.seller_id', $seller->id)
            ->groupBy('products.id')
            ->orderByRaw('products.id DESC')->get();
        return response()->json(['data' => $products]);
    }
    public function searchProducts(Request $request)
    {

        $image = DB::table('product_images')
            ->select('product_variant_id', 'image_url AS image', 'position')
            ->where('position', 1);
        $product_variants = DB::table('product_variants')
            ->select(
                'product_variants.id',
                'product_variants.sku',
                'product_variants.regular_price',
                'product_variants.product_id',
                'product_variants.stock_quantity',
                'image'
            )
            ->leftJoinSub($image, 'image', function ($join) {
                $join->on('product_variants.id', '=', 'image.product_variant_id');
            });


        $products = DB::table('products')
            ->where('seller_id', Auth::id())
            ->where('products.product_title', 'like', '%' . $request->searchString . '%')
            ->orWhere('product_variants.sku', '=', $request->searchString)
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoinSub($product_variants, 'product_variants', function ($join) use ($request) {
                $join->on('products.id', '=', 'product_variants.product_id');
            })
            ->select(
                'products.*',
                DB::raw('MIN(product_variants.regular_price) as minimum_price'),
                DB::raw('MAX(product_variants.regular_price) as maximum_price'),
                DB::raw('SUM(product_variants.stock_quantity) as stock_quantity'),
                DB::raw('COUNT(product_id) as variant_count'),
                'product_variants.image',
                'product_variants.sku',
                'categories.category_name as category_name'
            )
            ->groupBy('products.id')
            ->orderByRaw('products.id DESC')
            ->paginate(200);

        return response()->json($products);
    }
    public function store(Request $request)
    {
        if (!$request->product_id) {
            // Check if product already exist in products table 
            $product = DB::table('products')->where('product_title', $request->product_title)->first();
            $seller = DB::table('sellers')->where('id', Auth::id())->select('is_product_banned')->first();
            $product = Product::create([
                'product_title' => $request->product_title,
                'category_id' => $request->category_id,
                'unit' => $request->unit['unit'] ?  $request->unit['unit']  : 'pcs',
                'category_parent_id' => $request->category_parent_id,
                'category_second_level_id' => $request->category_second_level_id,
                'brand_id' => $request->brand['id'],
                'seller_id' => Auth::id(),
                'active' => !$seller->is_product_banned
            ]);
            //Create first variant for the Product.
            try {
                $product_variant =  $this->createVariant($request, $product);
                $this->createVariantMeta($request, $product_variant, $product);
                $this->createVariantService($request, $product_variant, $product);
            } catch (Throwable $e) {
                return response()->json(["id" => $product->id, "message" => "Something wrong happen, try again.", "status" => false]);
            }
            return response()->json(["id" => $product->id, "sku" => $product_variant->sku, "product_variant_id" => $product_variant->id, "message" => "Product <b>" .  $product->product_title . "</b> has been created with first variant.", "status" => true]);
        } else {
            $product = DB::table('products')->where('id', $request->product_id)->first();
            if ($product) {
                try {
                    $product_variant = $this->createVariant($request, $product);
                    $this->createVariantMeta($request, $product_variant, $product);
                    $this->createVariantService($request, $product_variant, $product);
                } catch (Throwable $e) {
                    return response()->json(['error' =>  dd($e), "id" => $product->id, "message" => "Something wrong happen, try again.", "status" => false]);
                }
                return response()->json(["id" => $product->id, "sku" => $product_variant->sku, "product_variant_id" => $product_variant->id, "message" => "A new variant for product <b>" .  $product->product_title . "</b> has been added.", "status" => true]);
            }
        }
    }
    public function update(Request $request)
    {
        $product_title = $request->product_title;
        $product_id = $request->id;

        if ($request->category['id'] && $request->category_first_level['id'] &&  $request->category_second_level['id'])
            Product::where('id', $product_id)->where('seller_id', Auth::id())->update([
                'product_title' => $product_title,
                'brand_id' => $request->brand['id'],
                'unit' => gettype($request->unit) === 'array' ? $request->unit['unit'] : $request->unit,
                'category_id' => $request->category['id'],
                'category_parent_id' => $request->category_first_level['id'],
                'category_second_level_id' => $request->category_second_level['id'],
            ]);
        else {
            Product::where('id', $product_id)->where('seller_id', Auth::id())->update([
                'product_title' => $product_title,
                'brand_id' => $request->brand['id'],
                'unit' => gettype($request->unit) === 'array' ? $request->unit['unit'] : $request->unit,
            ]);
        }

        $variants = DB::table('product_variants')->where('product_id', $product_id)->where('seller_id', Auth::id())->select('id')->get();

        $variant_ids = [];
        foreach ($variants as $variant) {
            array_push($variant_ids, $variant->id);
        }
        DB::table('product_variants')->whereIn('id', $variant_ids)->update([
            'product_title' => $product_title,
            'seller_id' => Auth::id()
        ]);
        return response()->json(["message" => "Product Updated", "status" => true]);
    }
    public function storeVariant(Request $request)
    {
        $product = DB::table('products')->where('id', $request->product_id)->where('seller_id', Auth::id())->first();
        if ($product) {
            try {
                $product_variant = $this->createVariant($request, $product);
                $this->createVariantMeta($request, $product_variant, $product);
                $this->createVariantService($request, $product_variant, $product);
            } catch (Throwable $e) {
                return response()->json(['error' =>  dd($e), "id" => $product->id, "message" => "Something wrong happen, try again.", "status" => false]);
            }
            return response()->json(["id" => $product->id, "sku" => $product_variant->sku, "product_variant_id" => $product_variant->id, "message" => "A new variant for product <b>" .  $product->product_title . "</b> has been added.", "status" => true]);
        }
        return response()->json(["message" => "Something wrong happen, try again.", "status" => false]);
    }
    public function createVariantService($request, $product_variant)
    {
        return ProductVariantService::create([
            'product_variant_id' => $product_variant->id,
            'delivery_charge' => $request->delivery_charge ? $request->delivery_charge : 0,
            'delivery_charge_outside' => $request->delivery_charge_outside,
            'free_delivery_upto' => $request->free_delivery_upto,
            'delivery_area' => $request->delivery_area,
            'payment_first' => $request->payment_first == true,
            'payment_first_amount_in_percentage' => $request->payment_first ? 10 : null,
            'payment_first_amount_in_taka' => $request->payment_first ? (($product_variant->offer_price != 0 || $product_variant->offer_price != null) ? ($product_variant->offer_price * 0.1) : ($product_variant->regular_price * 0.1)) : null,
            'replacement_in_days' => $request->replacement_in_days,
            'gurantee_in_months' => $request->gurantee_in_months,
            'warranty_in_months' => $request->warranty_in_months,
            'payment_first_delivery_charge' => $request->payment_first_delivery_charge == true
        ]);
    }
    public function createVariantMeta($request, $product_variant, $product)
    {
        return ProductVariantMeta::create([
            'product_variant_id' => $product_variant->id,
            'about_the_item' => $request->about_the_item,
            'product_description' => $request->product_description,
            'keywords' => Str::lower($request->keywords),
            'product_variant_embed_video_url' => $request->product_variant_embed_video_url,
            'product_components' => $request->product_components,
            'product_components_ratio_per_gram' => $request->component_ratio,
            'product_variant_url' =>  Str::slug($product->product_title, '-') . '/' . $product_variant->sku,
        ]);
    }
    public function createVariant($request, $product)
    {
        // Get Category Name 
        $category_name = Category::find($product->category_id)->category_name;
        // Make Sku
        $sku = substr(Str::upper($product->product_title), 0, 3) .
            rand(1, 100) .
            Str::upper(Str::random(5)) .
            substr(Str::upper($category_name), 0, 3);

        return ProductVariant::create([
            'product_id' => $product->id,
            'seller_id' => $product->seller_id,
            'sku' => $sku,
            'product_title' => $product->product_title,
            'product_variant_url' => Str::slug($product->product_title, '-') . '/' . $sku,
            'product_variant_title' => $request->product_variant_title ? $request->product_variant_title : $product->product_title,
            'regular_price' => $request->regular_price ? $request->regular_price : 0,
            'offer_price' => $request->offer_price ? $request->offer_price : 0,
            'discount_in_percentage' => $request->offer_price ? (intval(($request->offer_price / $request->regular_price - 1) * 100)) : 0,
            'stock_quantity' => $request->stock_quantity ? $request->stock_quantity : 0,
            'shape' => $request->shape,
            'item_diameter' => $request->item_diameter,
            'weight' => $request->weight,
            'authenticity' => $request->authenticity,
            'color' => $request->color ? $request->color['label'] : null,
            'color_code' => $request->color ? $request->color['code'] : null,
            'model_no' => $request->model_no,
            'country_of_origin' => $request->country_of_origin,
            'size' => $request->size,
            'material' => $request->material,
            'delivery_time' => $request->delivery_time ? $request->delivery_time : 1,
            'cash_on_delivery' => $request->cash_on_delivery
        ]);
    }
    public function destroy(Request $request)
    {
        $product = Product::find($request->id);
        if ($product) {
            $product_title = $product->product_title;
            ProductVariant::where('product_id', $product->id)->delete();
            $product->delete();
            return response()->json(['message' => "Product <b>" . $product_title . "</b> has been deleted.", "status" => true]);
        }
        return response()->json(['message' =>  "Already deleted.", "status" => false]);
    }
    private function destroyProductIfNoVariant($id)
    {
        Product::find($id)->delete();
        return true;
    }
    public function edit($id)
    {
        $product = Product::where('id', $id)->with(
            [
                "category",
                "category_second_level",
                "category_first_level",
                "seller",
                "brand"
            ]
        )->get();
        return response()->json($product[0]);
    }
    public function destroyVariant(Request $request)
    {
        $product_variant = ProductVariant::find($request->id);
        if ($product_variant) {
            $product_variant_title = $product_variant->product_variant_title;
            $product_id = $product_variant->product_id;
            $product_variant->delete();
            $product_have_variant = ProductVariant::where('product_id', $product_id)->first();
            if ($product_have_variant) {
                return response()->json(['message' => "Product Variant <b>" . $product_variant_title . "</b> has been deleted.", "status" => true]);
            } else {
                $this->destroyProductIfNoVariant($product_id);
                return response()->json(['message' => "Product Variant <b>" . $product_variant_title . "</b> has been deleted with <b> main product.</b>", "status" => true]);
            }
        }
        return response()->json(['message' =>  "Already deleted.", "status" => false]);
    }
    public function setOffer(Request $request)
    {
        $product = DB::table('products')->where('id', $request->product_id)->where('seller_id', Auth::id())->select('product_title')->first();
        if ($product) {
            $offer = DB::table('offers')->where('id', $request->offer_id)->select('offer_title')->first();
            if ($product && $offer) {
                Product::where('id', $request->product_id)->update([
                    'offer_id' => $request->offer_id
                ]);
                return response()->json(['message' => 'Product <b>' . $product->product_title . '</b> has been set to offer <b>' . $offer->offer_title . '</b>', 'status' => true]);
            }
        }
        return response()->json(['message' => 'Something went wrong! </br> Product or Offer is missing.', 'status' => false]);
    }
    public function removeOffer(Request $request)
    {
        $product = DB::table('products')->where('id', $request->product_id)->where('seller_id', Auth::id())->select('product_title')->first();
        if ($product) {
            Product::where('id', $request->product_id)->update([
                'offer_id' => null
            ]);
            return response()->json(['message' => 'Product <b>' . $product->product_title . '</b> has been removed from offer.', 'status' => true]);
        }
        return response()->json(['message' => 'Something went wrong!', 'status' => false]);
    }
}
