<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Resources\OrderCollection;
use App\Http\Controllers\Helpers\CommissionCalculator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('HasPermission:order-view')->only(['index', 'searchOrder']);
    }
    public function index()
    {
        $order_seen = DB::table('order_seen')
            ->select('order_id', 'user_id')
            ->where('user_id', '=', Auth::id());

        $orders = DB::table('orders')
            ->select('orders.id', 'orders.created_at', 'cart_total', 'customers.avatar as customer_avatar', 'customers.name as customer_name', 'orders.order_no', 'order_seen.user_id', 'order_seen.order_id')
            ->leftJoinSub($order_seen, 'order_seen', function ($join) {
                $join->on('orders.id', '=', 'order_seen.order_id');
            })->leftJoin('customers', 'orders.customer_id', '=', 'customers.id')
            ->orderBy('id', "DESC")
            ->paginate(100);

        $orderResource = new OrderCollection($orders);
        return  $orderResource;
    }
    public function searchOrder(Request $request)
    {
        $order_seen = DB::table('order_seen')
            ->select('order_id', 'user_id')
            ->where('user_id', '=', Auth::id());

        $orders = DB::table('orders')->where('orders.order_no', '=', $request->searchString)
            ->select('orders.id', 'orders.created_at', 'cart_total', 'customers.avatar as customer_avatar', 'customers.name as customer_name', 'orders.order_no', 'order_seen.user_id', 'order_seen.order_id')
            ->leftJoinSub($order_seen, 'order_seen', function ($join) {
                $join->on('orders.id', '=', 'order_seen.order_id');
            })->leftJoin('customers', 'orders.customer_id', '=', 'customers.id')
            ->orderBy('id', "DESC")
            ->paginate(100);

        $orderResource = new OrderCollection($orders);
        return  $orderResource;
    }

    public function showOrder($id)
    {
        //Update or Insert Order Seen
        DB::table('order_seen')->updateOrInsert([
            'order_id' => $id,
            'user_id' => Auth::id()
        ]);


        //Fetch Order Seller Information
        $order_products = DB::table('order_products')
            ->where('order_id', $id);

        //Fetch Order Shipping Information
        $order_shipping = DB::table('order_shipping_details')
            ->where('order_id', $id)->first();

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
                'order_products.category_id',
                'order_products.item_received',
                'order_products.item_returned',
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
                'order_products.status'
            )
            ->leftJoinSub($order_products, 'order_products', function ($join) {
                $join->on('order_sellers.seller_id', '=', 'order_products.seller_id');
            })
            ->where('order_sellers.order_id', $id);

        $order_items = DB::table('orders')
            ->select(
                'orders.id',
                'orders.order_no',
                'orders.created_at',
                'orders.customer_id',
                'orders.total_delivery_charge',
                'orders.cart_total_with_delivery',
                'orders.cart_total',
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
                'order_seller_products.item_received',
                'order_seller_products.item_returned',
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
                'order_seller_products.status'
            )
            ->leftJoinSub($order_seller_products, 'order_seller_products', function ($join) {
                $join->on('orders.id', '=', 'order_seller_products.order_id');
            })
            ->where('id', $id)->get();

        $order_info = [
            'order_no' => $order_items[0]->order_no,
            'created_at' => $order_items[0]->created_at,
            'receiver_name' => $order_shipping->receiver_name,
            'receiver_contact_no' => $order_shipping->receiver_contact_no,
            'receiver_address' => $order_shipping->address,
            'receiver_district' => $order_shipping->district,
            'receiver_area' => $order_shipping->area,
            'shipping_address_label' => $order_shipping->address_label,
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

    public function updateItemStatus(Request $request)
    {

        $product = DB::table('order_products')
            ->where('order_id', $request->order_id)
            ->where('product_variant_id', $request->id)
            ->where('item_received', false)->first();

        if ($product) {
            if ($request->status == 'received by moumita' && $product->status == 'delivered') {
                return $this->orderItemReceived($request);
            }
            if ($request->status == 'received by moumita' && $product->status != 'delivered') {
                return response()->json(['status' => false, 'message' => 'Something went wrong!']);
            }

            DB::table('order_products')
                ->where('order_id', $request->order_id)
                ->where('product_variant_id', $product->product_variant_id)
                ->where('item_received', false)
                ->update(['status' => $request->status]);
            return response()->json(['message' => "<b>" . $product->product_title  . "</b>'s status has been updated.", "status" => true]);
        }
        return response()->json(['message' =>  "Something went wrong!.", "status" => false]);
    }
    public function orderItemReceived(Request $request)
    {
        $order_product = DB::table('order_products')->where('order_id', $request->order_id)->where('product_variant_id', $request->id)->where('item_received', false)->where('item_returned', false)->first();
        if ($order_product) {

            $commission_rate['commission_rate'] = DB::table('sellers')->where('id', $order_product->seller_id)->select('commission_rate')->get()[0]->commission_rate;
            $commission_rate['current_commission'] = ($order_product->price * $order_product->quantity) * ((float)($commission_rate['commission_rate']) / 100);

            if ($commission_rate['commission_rate'] < 5) {
                $total_sale_amount = DB::table('order_products')->where(
                    'seller_id',
                    $order_product->seller_id
                )->where('item_received', 1)->sum('total_price');
                $commission_calculator = CommissionCalculator::open();
                $commission_rate = $commission_calculator->calculate($total_sale_amount, $order_product->total_price);
            }
            $update = DB::table('order_products')->where('order_id', $request->order_id)->where('product_variant_id', $request->id)
                ->update([
                    'commission_rate' => $commission_rate['commission_rate'],
                    'commission' => $commission_rate['current_commission'],
                    'item_received' => true,
                    'status' => 'received by customer'
                ]);

            if ($update) {
                $product_stock_quantity = DB::table('product_variants')->where('id', $request->id)->select('stock_quantity')->first();
                if ($product_stock_quantity) {
                    if ($product_stock_quantity->stock_quantity > 0) {
                        $updated_stock_quantity = $product_stock_quantity->stock_quantity - $order_product->quantity;
                        if ($updated_stock_quantity > 0) {
                            DB::table('product_variants')->where('id', $request->id)->update([
                                'stock_quantity' => $updated_stock_quantity
                            ]);
                        } else {
                            DB::table('product_variants')->where('id', $request->id)->update([
                                'stock_quantity' => 0
                            ]);
                        }
                    }
                }
                $product_title = $order_product->product_title;
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
}
