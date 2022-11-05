<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\CategoryCache;
use App\Models\Collection;
use App\Models\CustomerReview;
use App\Models\Offer;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ShopSorter;
use App\Http\Traits\ShopFilter;
use Illuminate\Support\Facades\DB;


class HomePageController extends Controller
{
    //
    use ShopSorter;
    use ShopFilter;
    public function main(Request $request)
    {
        // Log::channel('visitors')->info($request->ip());

        $banners = DB::table('banners')->orderBy('position')->get();
        $cacheData = DB::table('home_page_caches')->first();

        return view('frontPage', compact(['banners', 'cacheData']));
    }
    public function showProductsByCategory($url)
    {

        $category = Category::where('category_url', $url)->first();
        if (!$category) {
            return view('errors.404');
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

                'product_variants.image',
                'product_variants.product_variant_url'
            )
            ->where('products.active', true)
            ->where('products.category_id', $category->id)
            ->orWhere('products.category_parent_id', $category->id)
            ->orWhere('products.category_second_level_id', $category->id)
            ->groupBy('products.id')
            ->orderByRaw('products.id DESC')->get();

        return view('category', compact(['category',  'products']));
    }
    public function showProductsByBrand($slug)
    {
        $brand = Brand::where('slug', $slug)->first();
        if (!$brand) {
            return view('errors.404');
        }
        $image = DB::table('product_images')
            ->select('product_variant_id', 'thumbnail AS image', 'position')
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
            ->where('products.brand_id', $brand->id)
            ->where('products.active', true)
            ->groupBy('products.id')
            ->orderByRaw('products.id DESC')->get();

        return view('brand', compact(['brand', 'products']));
    }
    public function showProductsByOffer($slug)
    {
        $offer = Offer::where('slug', $slug)->first();
        if (!$offer) {
            return view('errors.404');
        }
        $image = DB::table('product_images')
            ->select('product_variant_id', 'thumbnail AS image', 'position')
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
            ->where('products.offer_id', $offer->id)
            ->where('products.active', true)
            ->groupBy('products.id')
            ->orderByRaw('products.id DESC')->get();

        return view('offer', compact(['offer', 'products']));
    }
    public function showProductsByCollection($slug)
    {
        $collection = Collection::where('slug', $slug)->first();
        if (!$collection) {
            return view('errors.404');
        }
        $collectionProductIDs = DB::table('collection_products')->where('collection_id', $collection->id)->pluck('product_id');
        if (count($collectionProductIDs)) {
            $image = DB::table('product_images')
                ->select('product_variant_id', 'thumbnail AS image', 'position')
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
                ->whereIn('products.id', $collectionProductIDs)
                ->where('products.active', true)
                ->groupBy('products.id')
                ->orderByRaw('products.id DESC')->get();

            return view('collection', compact(['collection', 'products']));
        } else {
            return view('errors.404');
        }
    }
    public function searchProduct(Request $request)
    {
        $query = $request->only('query');

        if (trim($query['query'], " ") == null) {
            return redirect('/');
        }

        $product = Product::where('product_title', 'like', '%' . $query['query'] . '%')->get();

        if (count($product) == 0) {
            return view('errors.404', compact(['query']));
        }
        $image = DB::table('product_images')
            ->select('product_variant_id', 'thumbnail AS image', 'position')
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
            ->where('products.product_title', 'like', '%' . $query['query'] . '%')
            ->where('products.active', true)
            ->groupBy('products.id')
            ->orderByRaw('products.id DESC')->get();

        return view('search', compact(['query',  'products']));
    }
    public function showProduct($product_url, $sku)
    {
        $product = ProductVariant::with([
            'product',
            'brand',
            'seller',
            'images' => function ($query) {
                return $query->orderBy('position');
            },
            'main_image',
            'meta',
            'services',
            'variants' => function ($query) {
                return $query->with([
                    'images',
                    'main_image',
                    'services',
                    'meta'
                ])->get();
            }
        ])
            ->where('product_variant_url', $product_url . '/' . $sku)->first();
        if (!$product) {
            return view('errors.404');
        }
        if (!$product->product->active) {
            return view('errors.404');
        }
        $keywords = $product->meta->keywords;

        $description = strip_tags($product->meta->about_the_item);

        $metaData = [
            'title' => $product->product_title,
            'description' => $description,
            'image' => $product->main_image->image_url
        ];
        $customer_reviews = CustomerReview::where('product_id', $product->product_id)->with(['customer' => function ($query) {
            return $query->select('id', 'name', 'avatar')->get();
        }])->orderBy('id', 'DESC')->get();

        $user_id = 0;
        if (Auth::guard('customer')->check()) {
            $user_id = Auth::guard('customer')->id();
        }
        $relatedProductIDs = DB::table('related_products')->where('main_product_id', $product->product_id)->pluck('related_product_id');

        $image = DB::table('product_images')
            ->select('product_variant_id', 'thumbnail AS image', 'position')
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
        $related_products = DB::table('products')
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
            ->whereIn('products.id', $relatedProductIDs)
            ->where('products.active', true)
            ->groupBy('products.id')
            ->orderByRaw('products.id DESC')->get();
        return view('product', compact(['user_id', 'product', 'related_products', 'customer_reviews', 'keywords', 'metaData']));
    }
    public function viewStore($slug)
    {
        $seller = Seller::where('shop_slug', $slug)->first();
        if (!$seller) {
            return view('errors.404');
        }
        return view('seller_shop', compact(['seller']));
    }
    public function fetchShopData(Request $request)
    {
        $seller = Seller::where('shop_slug', $request->shop_slug)->first();
        if (!$seller) {
            return view('errors.404');
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

                'product_variants.image',
                'product_variants.product_variant_url'
            )
            ->where('products.seller_id', $seller->id)
            ->where('products.active', true)
            ->groupBy('products.id')
            ->orderByRaw('products.id DESC')->get();
        $brand_IDs = DB::table('products')->select('brand_id')->where('seller_id', $seller->id)->distinct()->pluck('brand_id');
        $brands = DB::table('brands')->select('id', 'brand_name', 'slug')->whereIn('id', $brand_IDs)->get();
        return response()->json(['products' => $products, 'brands' => $brands]);
    }
    public function shopSortProducts(Request $request)
    {
        $seller = Seller::where('id', $request->seller_id)->first();
        if (!$seller) {
            return view('errors.404');
        }
        $products = $this->filter($request, $seller);
        $products = $this->sort($products, $request->option);
        return response()->json(['products' => $products]);
    }


    public function showAllSellers()
    {
        $sellers = DB::table('sellers')->where('active', 1)->orderBy('company_name')->get();
        return view('sellers', compact(['sellers']));
    }
    public function showAllBrands()
    {
        $brands = DB::table('brands')->orderBy('slug')->get();
        return view('brands', compact(['brands']));
    }
    public function showAllCollections()
    {
        $collections = DB::table('collections')->orderBy('id', 'DESC')->get();
        return view('collections', compact(['collections']));
    }
    public function showNewArrivals()
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
            ->where('products.active', true)
            ->groupBy('products.id')
            ->orderByRaw('products.id DESC')->limit(50)->get();

        return view('new-arrivals', compact(['products']));
    }
    public function dailyDeals()
    {
        $products = DB::table('home_page_caches')->select('daily_deals')->first();
        $products = json_decode($products->daily_deals);
        return view('daily-deals', compact(['products']));
    }
    public function subscribe(Request $request)
    {
        $already_subscribed = DB::table('subscribers')->where('email', $request->subscriber_email)->first();
        if ($already_subscribed) {
            return view('already_subscribed');
        }
        DB::table('subscribers')->insert([
            'email' => $request->subscriber_email
        ]);
        return view('subscribed_greetings');
    }
}
