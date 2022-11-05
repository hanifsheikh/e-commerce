<?php

namespace App\Http\Controllers;


use App\Http\Traits\OrderTrait;
use App\Models\CustomerShippingAddress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    use OrderTrait;
    public function __construct()
    {
        $this->middleware('auth:customer');
    }
    public function placeOrder(Request $request)
    {
        $shipping_address = CustomerShippingAddress::where('customer_id', Auth::id())->where('id', $request->address_id)->first();
        if (!$shipping_address) {
            return redirect()->route('select-shipping-address');
        }
        if (!$request->cart_data) {
            return redirect('/');
        };
        if (!count($request->cart_data)) {
            return redirect('/');
        };
        $processedData = $this->processOrderList($request);
        return view('orderlist')->with([
            'group_items_by_seller' => $processedData['group_items_by_seller'],
            'total_delivery_charge' => $processedData['total_delivery_charge'],
            'cart_total_with_delivery' => $processedData['cart_total_with_delivery'],
            'cart_total' => $processedData['cart_total'],
            'shipping_address' => $processedData['shipping_address'],
            'max_delivery_charge_array' => $processedData['max_delivery_charge_array']
        ]);
    }
    public function checkout(Request $request)
    {
        $processedData = $this->processOrderListToCheckout($request);
        if (!$processedData) {
            return redirect()->route('home-page');
        }
        $processedData['customer_id'] = Auth::id();
        // return $processedData;
        $orderAmountsBySeller = [];
        foreach ($processedData['group_items_by_seller'] as $seller_id => $sellerProducts) {
            $seller_order_amount = 0;
            foreach ($sellerProducts as $product) {
                if ($product->delivery_charge !== null) {
                    $seller_order_amount += $product->quantity * $product->price;
                }
            }
            if ($seller_order_amount) {
                $orderAmountsBySeller[$seller_id] = $seller_order_amount;
            }
        }
        $order_no = $this->getUniqueOrderNumber();
        // Insert into 'orders' table and get order_id 
        $order_id = DB::table('orders')->insertGetId([
            'order_no' => $order_no,
            'customer_id' => Auth::id(),
            'total_delivery_charge' => $processedData['total_delivery_charge'],
            'cart_total_with_delivery' => $processedData['cart_total_with_delivery'],
            'cart_total' => $processedData['cart_total'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        // Loop through sellers (orderAmountsBySeller) and insert into 'order_sellers' table
        $package_counter = 1;
        foreach ($orderAmountsBySeller as $seller_id => $seller_order_amount) {
            $seller = DB::table('sellers')->where('id', $seller_id)
                ->select('id', 'name', 'company_name', 'contact_no', 'logo')
                ->first();
            if ($seller) {
                DB::table('order_sellers')->insert([
                    'order_id' => $order_id,
                    'seller_id' => $seller->id,
                    'seller_name' => $seller->name,
                    'seller_company_name' => $seller->company_name,
                    'seller_contact_no' => $seller->contact_no,
                    'seller_logo' => $seller->logo,
                    'seller_order_amount' => $seller_order_amount,
                    'seller_order_delivery_charge' => $processedData['max_delivery_charge_array'][$seller->id],
                ]);
                DB::table('invoices')->insert([
                    'order_id' => $order_id,
                    'seller_id' => $seller->id,
                    'order_no' => $order_no,
                    'invoice_no' => $order_no . '-' . $package_counter,
                    'invoice_uuid' => $order_no . $package_counter . time() . Str::random(12) . rand(1, 50),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
                $package_counter++;
                // Loop seller's products and Insert into products (order_products) table
                foreach ($processedData['group_items_by_seller'][$seller_id] as $product) {
                    $images = DB::table('product_images')
                        ->where('product_variant_id', $product->variant_id)
                        ->select('image_url AS image')->pluck('image')->toArray();
                    DB::table('order_products')->insert([
                        'order_id' => $order_id,
                        'product_id' => $product->product_id,
                        'product_variant_id' => $product->variant_id,
                        'brand_id' => $product->brand_id,
                        'seller_id' => $product->seller_id,
                        'category_id' => $product->category_id,
                        'customer_id' => Auth::id(),
                        'brand_name' => $product->brand_name,
                        'product_title' => $product->product_title,
                        'product_variant_title' => $product->product_variant_title,
                        'quantity' => $product->quantity,
                        'sku' => $product->sku,
                        'shape' => $product->shape,
                        'unit' => $product->unit,
                        'item_diameter' => $product->item_diameter,
                        'weight' => $product->weight,
                        'authenticity' => $product->authenticity,
                        'color' => $product->color,
                        'color_code' => $product->color_code,
                        'texture' => $product->texture,
                        'model_no' => $product->model_no,
                        'country_of_origin' => $product->country_of_origin,
                        'size' => $product->size,
                        'material' => $product->material,
                        'stock_quantity' => $product->stock_quantity,
                        'regular_price' => $product->regular_price,
                        'offer_price' => $product->offer_price,
                        'price' => $product->price,
                        // Meta 
                        'about_the_item' => $product->about_the_item,
                        'product_description' => $product->product_description,
                        'product_variant_embed_video_url' => $product->product_variant_embed_video_url,
                        'product_components' => $product->product_components,
                        'product_components_ratio_per_gram' => $product->product_components_ratio_per_gram,
                        // Meta 
                        // Services 
                        'delivery_area' => $product->delivery_area,
                        'payment_first' => $product->payment_first,
                        'payment_first_amount_in_percentage' => $product->payment_first_amount_in_percentage,
                        'payment_first_amount_in_taka' => $product->payment_first_amount_in_taka,
                        'payment_first_delivery_charge' => $product->payment_first_delivery_charge,
                        'free_delivery_upto' => $product->free_delivery_upto,
                        'replacement_in_days' => $product->replacement_in_days,
                        'gurantee_in_months' => $product->gurantee_in_months,
                        'warranty_in_months' => $product->warranty_in_months,
                        'delivery_charge' => $product->delivery_charge,
                        'delivery_charge_outside' => $product->delivery_charge_outside,
                        // Services 
                        'total_price' => $product->price * $product->quantity,
                        'discount_in_percentage' => $product->discount_in_percentage,
                        'cash_on_delivery' => $product->cash_on_delivery,
                        'delivery_time' => Carbon::now()->addDays($product->delivery_time),
                        'product_variant_url' => $product->product_variant_url,
                        'image' => $product->image,
                        'images' => json_encode($images),
                        'status' => 'pending',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                    // Delete product from wishlist
                    DB::table('product_wishlists')->where('customer_id', Auth::id())->where('product_variant_id', $product->variant_id)->delete();
                }
            }
        }
        // Insert into 'order_shipping_details' table.
        DB::table('order_shipping_details')->insert([
            'order_id' => $order_id,
            'customer_id' => Auth::id(),
            'receiver_name' => $processedData['shipping_address']->receiver_name,
            'address_label' => $processedData['shipping_address']->label,
            'receiver_contact_no' => $processedData['shipping_address']->receiver_contact_no,
            'address' => $processedData['shipping_address']->receiver_address,
            'district' => $processedData['shipping_address']->district,
            'area' => $processedData['shipping_address']->area,
        ]);
        return view('order.greetings');
    }
    private function getUniqueOrderNumber()
    {
        $order_no =  Str::upper(Str::random(2)) .
            rand(1, 100) .
            rand(1, 100) .
            Str::upper(Str::random(2));
        $order_no_exist = DB::table('orders')->where('order_no', $order_no)->first();
        if ($order_no_exist) {
            $this->getUniqueOrderNumber();
        } else {
            return $order_no;
        }
    }
}
