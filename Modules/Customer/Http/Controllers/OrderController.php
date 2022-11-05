<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Helpers\CommissionCalculator;
use App\Models\Seller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }
    public function showOrderDetails($order_no)
    {
        // Check customer is valid for this order
        $order_customer = DB::table('orders')->where('customer_id', Auth::id())->where('order_no', $order_no)->first();
        if (!$order_customer) {
            return redirect()->back();
        }
        $cancellable = true;

        $not_cancellable = DB::table('order_products')->where('customer_id', Auth::id())
            ->where('order_id', $order_customer->id)
            ->whereIn(
                'status',
                [
                    'approved',
                    'ready to ship',
                    'delivered',
                    'received by customer',
                ]
            )->first();
        if ($not_cancellable) {
            $cancellable = false;
        }
        return view('customer::order.details')->with([
            'order_no' => $order_no,
            'cancellable' => $cancellable,
        ]);
    }
    public function fetchOrderDetailsData(Request $request)
    {
        // Check customer is valid for this order
        $order_customer = DB::table('orders')->where('customer_id', Auth::id())->where('order_no', $request->order_no)->first();
        if (!$order_customer) {
            return abort(401);
        }
        //Fetch Order Seller Information
        $order_products = DB::table('order_products')->select('order_products.*', 'customer_reviews.rating', 'customer_reviews.review_text')
            ->leftJoin('customer_reviews', 'order_products.product_id', '=', 'customer_reviews.product_id')->where('order_id', $order_customer->id);

        //Fetch Order Shipping Information
        $order_shipping = DB::table('order_shipping_details')
            ->where('order_id', $order_customer->id)->first();

        $order_seller_products = DB::table('order_sellers')
            ->select(
                'order_sellers.order_id',
                'order_sellers.seller_id',
                'order_sellers.seller_name',
                'order_sellers.seller_company_name',
                'order_sellers.seller_contact_no',
                'order_sellers.seller_logo',
                'order_sellers.seller_order_amount',
                'order_sellers.seller_order_delivery_charge',
                'order_products.product_variant_id',
                'order_products.brand_id',
                'order_products.product_id',
                'order_products.category_id',
                'order_products.brand_name',
                'order_products.product_title',
                'order_products.product_variant_title',
                'order_products.sku',
                'order_products.shape',
                'order_products.unit',
                'order_products.item_diameter',
                'order_products.weight',
                'order_products.authenticity',
                'order_products.color',
                'order_products.color_code',
                'order_products.texture',
                'order_products.model_no',
                'order_products.country_of_origin',
                'order_products.size',
                'order_products.material',
                'order_products.stock_quantity',
                'order_products.regular_price',
                'order_products.offer_price',
                'order_products.price',
                'order_products.quantity',
                'order_products.discount_in_percentage',
                'order_products.cash_on_delivery',
                'order_products.delivery_time',
                'order_products.delivery_area',
                'order_products.delivery_charge',
                'order_products.delivery_charge_outside',
                'order_products.free_delivery_upto',
                'order_products.product_variant_url',
                'order_products.image',
                'order_products.rating',
                'order_products.review_text',
                'order_products.status'
            )
            ->leftJoinSub($order_products, 'order_products', function ($join) {
                $join->on('order_sellers.seller_id', '=', 'order_products.seller_id');
            })

            ->where('order_sellers.order_id', $order_customer->id);

        $order_items = DB::table('orders')
            ->select(
                'orders.id',
                'orders.order_no',
                'orders.customer_id',
                'orders.total_delivery_charge',
                'orders.cart_total_with_delivery',
                'orders.cart_total',
                'order_seller_products.product_id',
                'order_seller_products.seller_id',
                'order_seller_products.seller_name',
                'order_seller_products.seller_company_name',
                'order_seller_products.seller_contact_no',
                'order_seller_products.seller_logo',
                'order_seller_products.seller_order_amount',
                'order_seller_products.seller_order_delivery_charge',
                'order_seller_products.product_variant_id',
                'order_seller_products.brand_id',
                'order_seller_products.category_id',
                'order_seller_products.brand_name',
                'order_seller_products.product_title',
                'order_seller_products.product_variant_title',
                'order_seller_products.sku',
                'order_seller_products.shape',
                'order_seller_products.unit',
                'order_seller_products.item_diameter',
                'order_seller_products.weight',
                'order_seller_products.authenticity',
                'order_seller_products.color',
                'order_seller_products.color_code',
                'order_seller_products.texture',
                'order_seller_products.model_no',
                'order_seller_products.country_of_origin',
                'order_seller_products.size',
                'order_seller_products.material',
                'order_seller_products.stock_quantity',
                'order_seller_products.regular_price',
                'order_seller_products.offer_price',
                'order_seller_products.price',
                'order_seller_products.quantity',
                'order_seller_products.discount_in_percentage',
                'order_seller_products.cash_on_delivery',
                'order_seller_products.delivery_time',
                'order_seller_products.delivery_area',
                'order_seller_products.delivery_charge',
                'order_seller_products.delivery_charge_outside',
                'order_seller_products.free_delivery_upto',
                'order_seller_products.product_variant_url',
                'order_seller_products.image',
                'order_seller_products.rating',
                'order_seller_products.review_text',
                'order_seller_products.status'
            )
            ->leftJoinSub($order_seller_products, 'order_seller_products', function ($join) {
                $join->on('orders.id', '=', 'order_seller_products.order_id');
            })
            ->where('customer_id', Auth::id())
            ->where('id', $order_customer->id)->get();

        $order_info = [
            'order_no' => $order_items[0]->order_no,
            'receiver_name' => $order_shipping->receiver_name,
            'shipping_address_label' => $order_shipping->address_label,
            'receiver_contact_no' => $order_shipping->receiver_contact_no,
            'receiver_address' => $order_shipping->address,
            'receiver_district' => $order_shipping->district,
            'receiver_area' => $order_shipping->area,
            'customer_id' => $order_items[0]->customer_id,
            'total_delivery_charge' => $order_items[0]->total_delivery_charge,
            'cart_total_with_delivery' => $order_items[0]->cart_total_with_delivery,
            'cart_total' => $order_items[0]->cart_total,
        ];

        $group_items_by_seller = [];
        foreach ($order_items as $item) {
            $group_items_by_seller[$item->seller_id][] = $item;
        }
        return response()->json([
            'order_info' => $order_info,
            'group_items_by_seller' => $group_items_by_seller
        ]);
    }
    public function orderItemReceived(Request $request)
    {

        //    Check if customer is related to this order item 
        $customer_order_product = DB::table('order_products')->where('order_id', $request->order_id)->where('customer_id', Auth::id())->where('product_variant_id', $request->item_id)->where('item_received', false)->where('item_returned', false)->first();
        if ($customer_order_product) {
            $commission_rate['commission_rate'] = DB::table('sellers')->where('id', $customer_order_product->seller_id)->select('commission_rate')->get()[0]->commission_rate;
            $commission_rate['current_commission'] = ($customer_order_product->price * $customer_order_product->quantity) * ((float)($commission_rate['commission_rate']) / 100);

            if ($commission_rate['commission_rate'] < 5) {
                $total_sale_amount = DB::table('order_products')->where(
                    'seller_id',
                    $customer_order_product->seller_id
                )->where('item_received', 1)->sum('total_price');
                $commission_calculator = CommissionCalculator::open();
                $commission_rate = $commission_calculator->calculate($total_sale_amount, $customer_order_product->total_price);
            }
            $update = DB::table('order_products')->where('order_id', $request->order_id)->where('customer_id', Auth::id())->where('product_variant_id', $request->item_id)
                ->update([
                    'commission_rate' => $commission_rate['commission_rate'],
                    'commission' => $commission_rate['current_commission'],
                    'item_received' => true,
                    'status' => 'received by customer'
                ]);

            if ($update) {
                $product_stock_quantity = DB::table('product_variants')->where('id', $request->item_id)->select('stock_quantity')->first();
                if ($product_stock_quantity) {
                    if ($product_stock_quantity->stock_quantity > 0) {
                        $updated_stock_quantity = $product_stock_quantity->stock_quantity - $customer_order_product->quantity;
                        if ($updated_stock_quantity > 0) {
                            DB::table('product_variants')->where('id', $request->item_id)->update([
                                'stock_quantity' => $updated_stock_quantity
                            ]);
                        } else {
                            DB::table('product_variants')->where('id', $request->item_id)->update([
                                'stock_quantity' => 0
                            ]);
                        }
                    }
                }
                $product_title = $customer_order_product->product_title;
                if (substr($product_title, -1) == ".") {
                    $product_title = substr_replace($product_title, "", -1);
                }

                return response()->json(['status' => true, 'message' => '<b class="font-bold">' . $product_title . '</b> has been received!']);
            }
            return response()->json(['status' => false, 'message' => 'Something went wrong!']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong!']);
        }
    }
    public function orderItemReturned(Request $request)
    {
        //    Check if customer is related to this order item 
        $customer_order_product = DB::table('order_products')->where('order_id', $request->order_id)->where('customer_id', Auth::id())->where('product_variant_id', $request->item_id)->where('item_received', false)->where('item_returned', false)->first();
        if ($customer_order_product) {
            $update = DB::table('order_products')->where('order_id', $request->order_id)->where('customer_id', Auth::id())->where('product_variant_id', $request->item_id)->update(['item_returned' => true, 'status' => 'product returned']);
            if ($update) {
                $product_title = $customer_order_product->product_title;
                if (substr($product_title, -1) == ".") {
                    $product_title = substr_replace($product_title, "", -1);
                }
                return response()->json(['status' => true, 'message' => '<b class="font-bold">' . $product_title . '</b> has been retuned!']);
            }
            return response()->json(['status' => false, 'message' => 'Something went wrong!']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong!']);
        }
    }
    public function productReview(Request $request)
    {
        $customerOrderExist = DB::table('order_products')->where('customer_id', Auth::id())->where('product_id', $request->product_id)->where('item_received', 1)->first();
        if (!$customerOrderExist) {
            return abort(401);
        }
        $reviewExist = DB::table('customer_reviews')->where('customer_id', Auth::id())->where('product_id', $request->product_id)->first();
        if ($reviewExist) {
            return response()->json(['status' => true, 'message' => 'You already reviewed this product.']);
        }
        if ($request->product_id && ($request->review_text || $request->rating)) {
            DB::table('customer_reviews')->insert([
                'customer_id' => Auth::id(),
                'product_id' => $request->product_id,
                'review_text' => $request->review_text,
                'rating' => $request->rating ? $request->rating : 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            return response()->json(['status' => true, 'message' => 'Thanks for your review!']);
        }
        return response()->json(['status' => false]);
    }
    public function updateProductReview(Request $request)
    {
        $customerOrderExist = DB::table('order_products')->where('customer_id', Auth::id())->where('product_id', $request->product_id)->where('item_received', 1)->first();
        if (!$customerOrderExist) {
            return abort(401);
        }
        $reviewExist = DB::table('customer_reviews')->where('customer_id', Auth::id())->where('product_id', $request->product_id)->first();
        if ($reviewExist) {
            if (trim($request->review_text) || $request->rating) {
                DB::table('customer_reviews')->where('customer_id', Auth::id())->where('product_id', $request->product_id)->update([
                    'review_text' => $request->review_text,
                    'rating' => $request->rating ? $request->rating : 0,
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('customer_reviews')->where('customer_id', Auth::id())->where('product_id', $request->product_id)->delete();
            }
        } else {
            if (trim($request->review_text) || $request->rating) {
                DB::table('customer_reviews')->insert([
                    'customer_id' => Auth::id(),
                    'product_id' => $request->product_id,
                    'review_text' => $request->review_text,
                    'rating' => $request->rating ? $request->rating : 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        return response()->json(['status' => true, 'message' => 'Product Review updated!']);
    }
    public function cancelOrder(Request $request)
    {
        $customerOrderExist = DB::table('orders')->where('customer_id', Auth::id())->where('order_no', $request->order_no)->first();
        if (!$customerOrderExist) {
            return response()->json(['status' => false, 'message' => 'Order is no longer available.']);
        }
        $not_cancellable = DB::table('order_products')->where('customer_id', Auth::id())
            ->where('order_id', $customerOrderExist->id)
            ->whereIn(
                'status',
                [
                    'approved',
                    'ready to ship',
                    'delivered',
                    'received by customer',
                ]
            )->first();
        if ($not_cancellable) {
            return response()->json(['status' => 'failed', 'message' => 'Order cannot be canceled. Because, this order was verified.']);
        }
        DB::table('invoices')->where('order_id', $customerOrderExist->id)->delete();
        DB::table('order_products')->where('customer_id', Auth::id())
            ->where('order_id', $customerOrderExist->id)->delete();
        DB::table('orders')->where('customer_id', Auth::id())->where('order_no', $request->order_no)->delete();
        return response()->json(['status' => true, 'message' => 'Order has been canceled!']);
    }
}
