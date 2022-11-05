<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\CategoryCache;
use App\Models\Seller;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Helpers\CommissionCalculator;

class RefreshCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refreshing Database Cache.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return $this->refreshCache();
    }
    private function refreshCache()
    {

        $sellers = Seller::all();
        foreach ($sellers as $seller) {

            // ** Insert/ Update previous month data cache starts here. 
            // Get Previous Month Data  
            $existingDataInPrevious = DB::table('seller_data_caches')->where(
                'seller_id',
                $seller->id
            )->whereMonth(
                'created_at',
                Carbon::now()->subMonth()->format('m')
            )->get();
            //  ** If Data is Exist update previous month seller_data_caches. **

            // Sum total sale amount of the seller in previous month
            $previous_month_total_sale_amount_in_current_month = DB::table('order_products')->where(
                'seller_id',
                $seller->id
            )->where('item_received', 1)->whereMonth(
                'created_at',
                Carbon::now()->subMonth()->format('m')
            )->sum('total_price');
            // Sum total sale amount of the seller in previous of previous month
            $previous_month_total_sale_amount_in_previous_month = DB::table('order_products')->where(
                'seller_id',
                $seller->id
            )->where('item_received', 1)->whereMonth(
                'created_at',
                Carbon::now()->subMonth(2)
            )->sum('total_price');
            // Total accumulative sale amount of a seller until now.
            $previous_month_total_sale_amount = $previous_month_total_sale_amount_in_current_month + $previous_month_total_sale_amount_in_previous_month;
            // Check total sale amount and assign Commission Rate
            $commission_calculator = CommissionCalculator::open();
            $previous_month_commission_rate = $commission_calculator->calculate($previous_month_total_sale_amount, 0);

            $get_seller_payments_previous_month = DB::table('seller_payments')->where(
                'seller_id',
                $seller->id
            )->whereMonth(
                'payment_of',
                Carbon::now()->subMonth()->format('m')
            )->get();
            $due_in_previous_month = 0;
            foreach ($get_seller_payments_previous_month as $payment) {
                $due_in_previous_month += $payment->paid_to_admin;
                $due_in_previous_month -= $payment->received_from_admin;
            }

            // Get Total Commission Amount of current of previous month
            $previous_month_total_commission_amount_in_current_month = DB::table('order_products')->where(
                'seller_id',
                $seller->id
            )->where('item_received', 1)->whereMonth(
                'created_at',
                Carbon::now()->subMonth()->format('m')
            )->sum('commission');
            $due_in_previous_month = $previous_month_commission_rate['total_commission'] + -$due_in_previous_month;

            if (count($existingDataInPrevious)) {
                DB::table('seller_data_caches')->where(
                    'seller_id',
                    $seller->id
                )->whereMonth(
                    'created_at',
                    Carbon::now()->subMonth()->format('m')
                )->update([
                    "due" => $due_in_previous_month,
                    'current_commission_rate' => $previous_month_commission_rate['commission_rate'],
                    "total_sale_amount" => $previous_month_total_sale_amount,
                    "total_sale_amount_in_current_month" => $previous_month_total_sale_amount_in_current_month,
                    "total_sale_amount_in_previous_month" => $previous_month_total_sale_amount_in_previous_month,
                    "total_commission_in_current_month" => $previous_month_total_commission_amount_in_current_month,
                ]);
            } else {
                DB::table('seller_data_caches')->insert([
                    "seller_id" => $seller->id,
                    "due" => $due_in_previous_month,
                    'current_commission_rate' => $previous_month_commission_rate['commission_rate'],
                    "total_sale_amount" => $previous_month_total_sale_amount,
                    "total_sale_amount_in_current_month" => $previous_month_total_sale_amount_in_current_month,
                    "total_sale_amount_in_previous_month" => $previous_month_total_sale_amount_in_previous_month,
                    "total_commission_in_current_month" => $previous_month_total_commission_amount_in_current_month,
                    'created_at' => Carbon::now()->subMonth()->endOfMonth()->toDateString()
                ]);
            }
            // ** Insert/ Update previous month data cache ends here. 

            // ** Insert/ Update current month data cache starts here. 
            // Sum total sale amount of the seller in current month
            $total_sale_amount_in_current_month = DB::table('order_products')->where(
                'seller_id',
                $seller->id
            )->where('item_received', 1)->whereMonth(
                'created_at',
                Carbon::now()->format('m')
            )->sum('total_price');
            // Sum total sale amount of the seller in previous month
            $total_sale_amount_in_previous_month = DB::table('order_products')->where(
                'seller_id',
                $seller->id
            )->where('item_received', 1)->whereMonth(
                'created_at',
                Carbon::now()->subMonth()
            )->sum('total_price');
            // Total accumulative sale amount of a seller until now.
            $total_sale_amount = $total_sale_amount_in_current_month + $total_sale_amount_in_previous_month;
            // Check total sale amount and assign Commission Rate
            $commission_calculator = CommissionCalculator::open();
            $commission_rate = $commission_calculator->calculate($total_sale_amount, 0);

            $get_seller_payments = DB::table('seller_payments')->where(
                'seller_id',
                $seller->id
            )->whereMonth(
                'payment_of',
                Carbon::now()->format('m')
            )->get();
            $due = 0;
            foreach ($get_seller_payments as $payment) {
                $due += $payment->paid_to_admin;
                $due -= $payment->received_from_admin;
            }
            // Sum total sale amount of the seller in current month
            $total_commission_ammount_in_current_month = DB::table('order_products')->where(
                'seller_id',
                $seller->id
            )->where('item_received', 1)->whereMonth(
                'created_at',
                Carbon::now()->format('m')
            )->sum('commission');
            $due = $total_commission_ammount_in_current_month + -$due;

            // Get Current Month Data 
            $existingData = DB::table('seller_data_caches')->where(
                'seller_id',
                $seller->id
            )->whereMonth(
                'created_at',
                Carbon::now()->format('m')
            )->get();


            //  ** If Data is Exist update current month seller_data_caches. **
            if (count($existingData)) {
                DB::table('seller_data_caches')->where(
                    'seller_id',
                    $seller->id
                )->whereMonth(
                    'created_at',
                    Carbon::now()->format('m')
                )->update([
                    "due" => $due,
                    "current_commission_rate" => $commission_rate['commission_rate'],
                    "total_sale_amount" => $total_sale_amount,
                    "total_sale_amount_in_current_month" => $total_sale_amount_in_current_month,
                    "total_sale_amount_in_previous_month" => $total_sale_amount_in_previous_month,
                    "total_commission_in_current_month" => $total_commission_ammount_in_current_month,
                    'created_at' => Carbon::now()
                ]);
            }
            // ** If Data is not exist insert new data to  current month of seller_data_caches. **
            else {
                DB::table('seller_data_caches')->insert([
                    "seller_id" => $seller->id,
                    "due" => $due,
                    "current_commission_rate" => $commission_rate['commission_rate'],
                    "total_sale_amount" => $total_sale_amount,
                    "total_sale_amount_in_current_month" => $total_sale_amount_in_current_month,
                    "total_sale_amount_in_previous_month" => $total_sale_amount_in_previous_month,
                    "total_commission_in_current_month" => $total_commission_ammount_in_current_month,
                    'created_at' => Carbon::now()
                ]);
            }
            // ** Insert/ Update current month data cache ends here. 

            //  ** Update the commission rate of the seller **
            DB::table('sellers')->where('id', $seller->id)->update(['commission_rate' => $commission_rate['commission_rate']]);
        }

        // ** Get summation of current month sales commision **
        $sum_of_commission = DB::table('order_products')->whereMonth('created_at', Carbon::now()->format('m'))->where('item_received', 1)->sum('commission');

        // ** Get summation of previous month sales commision **
        $sum_of_commission_previous_month = DB::table('order_products')->whereMonth('created_at', Carbon::now()->subMonth()->format('m'))->where('item_received', 1)->sum('commission');

        // ** Get total orders in this month **
        $total_orders_in_current_month = DB::table('orders')->whereMonth('created_at', Carbon::now()->format('m'))->count();

        // ** Get total orders in previous month **
        $total_orders_in_previous_month = DB::table('orders')->whereMonth('created_at', Carbon::now()->subMonth()->format('m'))->count();

        // ** Delete Current month Admin Dashboard Cache data **
        $existingData = DB::table('dashboard_caches')->whereMonth(
            'created_at',
            Carbon::now()->format('m')
        )->get();
        if (count($existingData)) {
            DB::table('dashboard_caches')->whereMonth(
                'created_at',
                Carbon::now()->format('m')
            )->delete();
        }
        // ** Insert New Data to Current month Admin Dashboard Cache data **
        DB::table('dashboard_caches')->insert([
            "sum_of_commission" => $sum_of_commission,
            "sum_of_commission_previous_month" => $sum_of_commission_previous_month,
            "total_orders_in_current_month" => $total_orders_in_current_month,
            "total_orders_in_previous_month" => $total_orders_in_previous_month,
            "created_at" => Carbon::now(),
        ]);

        // Refresh Category Cache 
        $this->refreshCategoryCache();

        // Refresh Best Selling Cache 
        $this->refreshBestSellingCache();

        // Refresh Daily Deals Cache 
        $this->refreshDailyDealsCache();

        // Refresh Category Products Cache 
        $this->refreshCategoryProductCache();

        // Refresh Collections Cache 
        $this->refreshCollectionCache();

        // Refresh Brand Cache 
        $this->refreshBrandCache();
        // Refresh Brand Cache 
        $this->refreshProductCache();

        // Refersh SellerCache 
        $this->refreshSellerCache();
    }

    private function refreshCategoryCache()
    {
        // Fetch All categories with their childrens
        $categories = Category::with(['childrens' => function ($query) {
            return $query->with(['childrens' => function ($q) {
                return $q->with('parent')->get();
            }, 'parent'])->get();
        }])->where('parent_id', null)->get();

        // Parent Array 
        $parents = [];
        $homepage_cache_parents = [];

        // Loop through each categories and put in the $parents[] Array by key => value pair.
        foreach ($categories as $category) {

            // Homepage Category Cache Starts Here 
            $category_have_product = DB::table('products')
                ->where('category_id', $category->id)
                ->orWhere('category_second_level_id', $category->id)
                ->orWhere('category_parent_id', $category->id)
                ->first();
            // if have products in main parent 
            if ($category_have_product) {
                $cache_main_parent = $category;
                // Before insert in hompage_cache_parent array check second level childrens have products  
                $homepage_cache_second_levels = [];
                foreach ($cache_main_parent->childrens as $cache_second_level_parent) {
                    $second_level_category_have_product = DB::table('products')
                        ->where('category_id', $cache_second_level_parent->id)
                        ->orWhere('category_second_level_id', $cache_second_level_parent->id)
                        ->orWhere('category_parent_id', $cache_second_level_parent->id)
                        ->first();
                    // if have products in second level parent 
                    if ($second_level_category_have_product) {
                        // Before insert in homepage_cache_second_levels array check third level childrens have products 
                        $homepage_cache_third_levels = [];
                        foreach ($cache_second_level_parent->childrens as $cache_third_level) {
                            $third_level_category_have_product = DB::table('products')
                                ->where('category_id', $cache_third_level->id)
                                ->first();
                            if ($third_level_category_have_product) {
                                array_push($homepage_cache_third_levels,  $cache_third_level);
                            }
                        }
                        $second_level = new \stdClass();
                        $second_level->id = $cache_second_level_parent->id;
                        $second_level->parent_id = $cache_second_level_parent->parent_id;
                        $second_level->category_name = $cache_second_level_parent->category_name;
                        $second_level->category_url = $cache_second_level_parent->category_url;
                        $second_level->childrens = $homepage_cache_third_levels;
                        array_push($homepage_cache_second_levels,  $second_level);
                    }
                }
                usort($homepage_cache_second_levels, function ($a, $b) {
                    return count($a->childrens) < count($b->childrens);
                });

                $homepage_cache_parents[$cache_main_parent->category_name] = [
                    'id' => $cache_main_parent->id,
                    'category_name' => $cache_main_parent->category_name,
                    'category_url' => $cache_main_parent->category_url,
                    'category_image' => $cache_main_parent->category_image,
                    'category_thumbnail' => $cache_main_parent->category_thumbnail,
                    'childrens' => $homepage_cache_second_levels,
                ];
            }

            // End of Homepage Category cache.
            $parents[$category->category_name] = [
                'id' => $category->id,
                'category_url' => $category->category_url,
                'category_image' => $category->category_image,
                'category_thumbnail' => $category->category_thumbnail,
                'childrens' => $category->childrens,
            ];
        }
        //Sort Parents Array by Key.
        ksort($parents);
        sort($homepage_cache_parents);

        // Put the Array elements into 'category_caches' table this will load every time user requests. 
        DB::table('category_caches')->truncate();
        foreach ($parents as $parent => $childrens) {
            CategoryCache::create([
                'category_name' => $parent,
                'id' => $childrens['id'],
                'category_url' => $childrens['category_url'],
                'category_image' => $childrens['category_image'],
                'category_thumbnail' => $childrens['category_thumbnail'],
                'childrens' => $childrens['childrens']
            ]);
        }

        DB::table('home_page_caches')->update([
            'category_caches' => json_encode($homepage_cache_parents)
        ]);
    }
    private function refreshBrandCache()
    {
        $brands = DB::table('brands')->orderBy('id', 'DESC')->get()->take(24);
        DB::table('home_page_caches')->update([
            'brands' => json_encode($brands)
        ]);
    }
    private function refreshProductCache()
    {
        $product_reviews = DB::table('customer_reviews')->where('rating', '>', 0)->select('product_id', DB::raw('sum(rating) as total_rating'), DB::raw('count(*) as total_reviews'))->groupBy('product_id')->get();

        foreach ($product_reviews as $key => $product_review) {
            $rating = (float)($product_review->total_rating / $product_review->total_reviews);
            DB::table('products')->where('id', $product_review->product_id)->update([
                'ratings' => $rating
            ]);
        }
    }
    private function refreshSellerCache()
    {
        $sellers = DB::table('sellers')->select('id', 'shop_slug', 'company_name', 'logo')
            ->where('active', true)
            ->groupBy('company_name')
            ->orderBy('id', 'DESC')
            ->get()->take(10);
        DB::table('home_page_caches')->update([
            'sellers' => json_encode($sellers)
        ]);
    }
    private function refreshCollectionCache()
    {
        $collections = DB::table('collections')->orderBy('id', 'DESC')->get()->take(10);
        DB::table('home_page_caches')->update([
            'collections' => json_encode($collections)
        ]);
    }
    private function refreshCategoryProductCache()
    {
        // Category Product Caches for HomePage
        $category_products = [];
        $limit = 15; // Set the limit of products here
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
            ->where('products.active', true)
            ->groupBy('products.id')
            ->orderByRaw('products.id DESC')->get()->toArray();
        $categories = DB::table('categories')->where('parent_id', null)->orderBy('id')->get();
        foreach ($categories as $category) {
            $i = 0;
            foreach ($products as $product) {
                if ($category->id == $product->category_parent_id) {
                    $product->category_name = $category->category_name;
                    $product->category_url = $category->category_url;
                    $category_products[$category->id][$i] = $product;
                    $i++;
                    if ($i == $limit) {
                        break;
                    }
                }
            }
        }
        DB::table('home_page_caches')->update([
            'category_products' => json_encode($category_products)
        ]);
    }
    private function refreshDailyDealsCache()
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
                DB::raw('COUNT(product_id) as variant_count'),
                'product_variants.image',
                'product_variants.product_variant_url'
            )
            ->where('products.active', true)
            ->groupBy('products.id')
            ->orderByRaw('products.id DESC')->limit(10)->get();
        DB::table('home_page_caches')->update([
            'daily_deals' => json_encode($products)
        ]);
    }
    private function refreshBestSellingCache()
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
                DB::raw('COUNT(product_id) as variant_count'),
                'product_variants.image',
                'product_variants.product_variant_url'
            )
            ->where('products.active', true)
            ->groupBy('products.id')
            ->orderByRaw('products.id DESC')->limit(12)->get();
        DB::table('home_page_caches')->update([
            'best_selling' => json_encode($products)
        ]);
    }
}
