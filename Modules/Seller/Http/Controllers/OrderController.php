<?php

namespace Modules\Seller\Http\Controllers;

use App\Http\Resources\OrderCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
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
            })
            ->join('order_sellers', 'orders.id', '=', 'order_sellers.order_id')
            ->where('order_sellers.seller_id', Auth::id())
            ->leftJoin('customers', 'orders.customer_id', '=', 'customers.id')
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
            })
            ->join('order_sellers', 'orders.id', '=', 'order_sellers.order_id')
            ->where('order_sellers.seller_id', Auth::id())
            ->leftJoin('customers', 'orders.customer_id', '=', 'customers.id')
            ->orderBy('id', "DESC")
            ->paginate(100);

        $orderResource = new OrderCollection($orders);
        return $orderResource;
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
            ->where('seller_id', Auth::id())
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
            ->where('order_sellers.seller_id', Auth::id())
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
            'id' => $order_items[0]->id,
            'order_no' => $order_items[0]->order_no,
            'created_at' => $order_items[0]->created_at,
            'receiver_name' => $order_shipping->receiver_name,
            'receiver_contact_no' => $order_shipping->receiver_contact_no,
            'receiver_address' => $order_shipping->address,
            'receiver_district' => $order_shipping->district,
            'receiver_area' => $order_shipping->area,
            'shipping_address_label' => $order_shipping->address_label,
            'customer_id' => $order_items[0]->customer_id,
            'total_delivery_charge' => $order_items[0]->seller_order_delivery_charge,
            'cart_total_with_delivery' => $order_items[0]->seller_order_delivery_charge + $order_items[0]->seller_order_amount,
            'cart_total' => $order_items[0]->seller_order_amount,
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
        if (!Auth::user()) {
            return abort(401);
        }
        if ($request->status == 'canceled') {
            return response()->json(['message' => "You don't have right to cancel order.", 'status' => false]);
        }
        if ($request->status == 'approved') {
            return response()->json(['message' => "You don't have right to approve order.", 'status' => false]);
        }
        $product = DB::table('order_products')
            ->where('order_id', $request->order_id)
            ->where('product_variant_id', $request->id)
            ->where('seller_id', Auth::id())
            ->where('item_received', false)->first();

        if ($product) {
            DB::table('order_products')
                ->where('order_id', $request->order_id)
                ->where('product_variant_id', $product->product_variant_id)
                ->where('seller_id', Auth::id())
                ->where('item_received', false)
                ->update(['status' => $request->status]);
            return response()->json(['message' => "<b>" . $product->product_title  . "</b>'s status has been updated.", "status" => true]);
        }
        return response()->json(['message' =>  "Something went wrong!.", "status" => false]);
    }
    public function downloadInvoice(Request $request)
    {
        $id = $request->id;
        //Fetch Order Seller Information
        $order_products = DB::table('order_products')
            ->where('seller_id', Auth::id())
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
            ->where('order_sellers.seller_id', Auth::id())
            ->where('order_sellers.order_id', $id);

        $order_items = DB::table('orders')
            ->select(
                'orders.id',
                'orders.order_no',
                'orders.created_at',
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
            ->whereIn('order_seller_products.status', ['ready to ship', 'delivered'])
            ->where('id', $id)->get();
        if (count($order_items) == 0) {
            return;
        }
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
            'total_delivery_charge' => $order_items[0]->seller_order_delivery_charge,
            'cart_total_with_delivery' => $order_items[0]->seller_order_delivery_charge + $order_items[0]->seller_order_amount,
            'cart_total' => $order_items[0]->seller_order_amount,
        ];

        $invoice = DB::table('invoices')->select('invoice_uuid', 'invoice_no')->where('order_no', $order_info['order_no'])->where('seller_id', Auth::id())->first();
        $qrcode = base64_encode(QrCode::format('svg')->size(128)->errorCorrection('H')->generate(config('app.url') . '/order/downloadInvoice/' . $invoice->invoice_uuid));
        $data = [
            'order_info' => $order_info,
            'invoice_no' => $invoice->invoice_no,
            'invoice_date' => Carbon::now()->toFormattedDateString(),
            'order_date' => Carbon::parse($order_items[0]->created_at)->toFormattedDateString(),
            'qrcode' => $qrcode,
            'items' => $order_items
        ];

        $pdf = PDF::loadView('seller::invoice', $data);
        return $pdf->download('Invoice - ' . $invoice->invoice_no . '.pdf');
    }
}
