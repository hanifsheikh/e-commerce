<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvoiceController extends Controller
{
    public function download($invoice_uuid)
    {
        $invoiceExist = Invoice::where('invoice_uuid', $invoice_uuid)->first();
        if (!$invoiceExist) {
            return view('errors.404');
        }
        $id = $invoiceExist->order_id;
        //Fetch Order Seller Information
        $order_products = DB::table('order_products')
            ->where('seller_id', $invoiceExist->seller_id)
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
            ->where('order_sellers.seller_id', $invoiceExist->seller_id)
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
            ->whereIn('order_seller_products.status', ['ready to ship', 'delivered'])
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
            'total_delivery_charge' => $order_items[0]->seller_order_delivery_charge,
            'cart_total_with_delivery' => $order_items[0]->seller_order_delivery_charge + $order_items[0]->seller_order_amount,
            'cart_total' => $order_items[0]->seller_order_amount,
        ];

        $invoice = DB::table('invoices')->select('invoice_uuid', 'invoice_no')->where('order_no', $order_info['order_no'])->where('seller_id', $invoiceExist->seller_id)->first();
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
        return response($pdf->download('Invoice - ' . $invoice->invoice_no . '.pdf'))
            ->header("Content-disposition", "attachment; filename=" . 'Invoice - ' . $invoice->invoice_no . '.pdf');
    }
}
