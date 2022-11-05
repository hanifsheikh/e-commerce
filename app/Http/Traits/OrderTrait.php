<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait OrderTrait
{
    public function processOrderList(Request $request)
    {
        if ($request->cart_data === null) {
            return;
        }
        $shipping_address = DB::table('customer_shipping_addresses')->where('id', $request->address_id)->first();
        if (!$shipping_address) {
            return;
        }
        $cart_items = [];
        $variant_ids = [];
        $max_delivery_charge_array = [];
        $cart_total_with_delivery = 0;
        $cart_total = 0;
        $total_delivery_charge = 0;
        foreach ($request->cart_data as $item) {
            array_push($cart_items, json_decode($item, true));
        }
        foreach ($cart_items as $cart_item) {
            array_push($variant_ids, $cart_item['id']);
        }
        $product_seller_brand = DB::table('products')
            ->select('products.id as product_id', 'unit', 'brand_id', 'brand_name', 'seller_id', 'product_title', 'category_id', 'company_name as seller_company')
            ->join('sellers', 'products.seller_id', '=', 'sellers.id')
            ->join('brands', 'products.brand_id', '=', 'brands.id');
        $service = DB::table('product_variant_services')
            ->select(
                'product_variant_id',
                'delivery_charge',
                'free_delivery_upto',
                'delivery_area',
                'replacement_in_days',
                'warranty_in_months',
                'gurantee_in_months',
                'delivery_charge_outside',
                'payment_first',
                'payment_first_amount_in_percentage',
                'payment_first_amount_in_taka',
                'payment_first_delivery_charge'
            );
        $image = DB::table('product_images')
            ->select('product_variant_id', 'image_url AS image')
            ->where('position', 1);
        $items = DB::table('product_variants')
            ->select(
                'product_variants.seller_id',
                'product_variants.product_id',
                'brand_id',
                'category_id',
                'brand_name',
                'shape',
                'item_diameter',
                'weight',
                'authenticity',
                'seller_company',
                'product_variants.product_title',
                'product_variants.product_variant_title',
                'product_seller.unit',
                'image',
                'sku',
                'color',
                'color_code',
                'texture',
                'model_no',
                'country_of_origin',
                'material',
                'cash_on_delivery',
                'stock_quantity',
                'size',
                'id as variant_id',
                'discount_in_percentage',
                'delivery_time',
                'delivery_charge as product_delivery_charge',
                'regular_price',
                'offer_price',
                'product_variant_url',
                // services 
                'delivery_charge',
                'free_delivery_upto',
                'delivery_area',
                'replacement_in_days',
                'warranty_in_months',
                'gurantee_in_months',
                'delivery_charge_outside',
                'payment_first',
                'payment_first_amount_in_percentage',
                'payment_first_amount_in_taka',
                'payment_first_delivery_charge'
                // services
            )
            ->leftJoinSub($image, 'image', function ($join) {
                $join->on('product_variants.id', '=', 'image.product_variant_id');
            })
            ->leftJoinSub($product_seller_brand, 'product_seller', function ($join) {
                $join->on('product_variants.product_id', '=', 'product_seller.product_id');
            })
            ->leftJoinSub($service, 'service', function ($join) {
                $join->on('product_variants.id', '=', 'service.product_variant_id');
            })
            ->whereIn('id', $variant_ids)
            ->get();

        $group_items_by_seller = [];
        foreach ($items as $item) {
            $group_items_by_seller[$item->seller_id][] = $item;
        }
        foreach ($items as $item) {
            foreach ($cart_items as $cart_item) {
                if ($item->variant_id === $cart_item['id']) {
                    $item->quantity = $cart_item['quantity'];
                    $item->price = $item->offer_price
                        ? $item->offer_price
                        : $item->regular_price;
                    if ($item->delivery_area === $shipping_address->district) {
                        $cart_total += $item->quantity *  $item->price;
                    } elseif ($item->delivery_charge_outside) {
                        $cart_total += $item->quantity *  $item->price;
                    }
                }
            }
        }
        foreach ($group_items_by_seller as $key => $group) {
            $total_sale = 0;
            $max_delivery_charge = 0;
            foreach ($group as $item) {
                if ($item->delivery_area === $shipping_address->district || $item->delivery_charge_outside) {
                    $total_sale += $item->quantity * $item->price;
                }
            }
            foreach ($group as $item) {
                if ($total_sale >= $item->free_delivery_upto && $item->free_delivery_upto != 0 && $item->delivery_area === $shipping_address->district) {
                    $item->delivery_charge = 0;
                } else {
                    if ($item->delivery_area != $shipping_address->district) {
                        $item->delivery_charge = $item->delivery_charge_outside;
                    }
                }
                $max_delivery_charge = ($max_delivery_charge < $item->delivery_charge) ? $item->delivery_charge  : $max_delivery_charge;
            }
            $max_delivery_charge_array[$key] = $max_delivery_charge;
        }
        foreach ($max_delivery_charge_array as $key => $charge) {
            $total_delivery_charge += $charge;
        }
        $cart_total_with_delivery = $cart_total + $total_delivery_charge;
        return [
            'group_items_by_seller' => $group_items_by_seller,
            'total_delivery_charge' => $total_delivery_charge,
            'cart_total_with_delivery' => $cart_total_with_delivery,
            'cart_total' => $cart_total,
            'shipping_address' => $shipping_address,
            'max_delivery_charge_array' => $max_delivery_charge_array
        ];
    }

    public function processOrderListToCheckout(Request $request)
    {
        if ($request->cart_data === null) {
            return;
        }
        $shipping_address = DB::table('customer_shipping_addresses')->where('id', $request->address_id)->first();
        if (!$shipping_address) {
            return;
        }
        $cart_items = [];
        $variant_ids = [];
        $max_delivery_charge_array = [];
        $cart_total_with_delivery = 0;
        $cart_total = 0;
        $total_delivery_charge = 0;
        foreach ($request->cart_data as $item) {
            array_push($cart_items, json_decode($item, true));
        }
        foreach ($cart_items as $cart_item) {
            array_push($variant_ids, $cart_item['id']);
        }
        $product_seller_brand = DB::table('products')
            ->select('products.id as product_id', 'unit', 'brand_id', 'brand_name', 'seller_id', 'product_title', 'category_id', 'company_name as seller_company')
            ->join('sellers', 'products.seller_id', '=', 'sellers.id')
            ->join('brands', 'products.brand_id', '=', 'brands.id');
        $service = DB::table('product_variant_services')
            ->select(
                'product_variant_id',
                'delivery_charge',
                'free_delivery_upto',
                'delivery_area',
                'replacement_in_days',
                'warranty_in_months',
                'gurantee_in_months',
                'delivery_charge_outside',
                'payment_first',
                'payment_first_amount_in_percentage',
                'payment_first_amount_in_taka',
                'payment_first_delivery_charge'
            );
        $meta = DB::table('product_variant_metas')
            ->select(
                'product_variant_id',
                'about_the_item',
                'product_description',
                'product_variant_embed_video_url',
                'product_components',
                'product_components_ratio_per_gram'
            );
        $image = DB::table('product_images')
            ->select('product_variant_id', 'image_url AS image')
            ->where('position', 1);
        $items = DB::table('product_variants')
            ->select(
                'product_variants.seller_id',
                'product_variants.product_id',
                'brand_id',
                'category_id',
                'brand_name',
                'shape',
                'item_diameter',
                'weight',
                'authenticity',
                'seller_company',
                'product_variants.product_title',
                'product_variants.product_variant_title',
                'product_seller.unit',
                'image',
                'sku',
                'color',
                'color_code',
                'texture',
                'model_no',
                'country_of_origin',
                'material',
                'cash_on_delivery',
                'stock_quantity',
                'size',
                'id as variant_id',
                'discount_in_percentage',
                'delivery_time',
                'delivery_charge as product_delivery_charge',
                'regular_price',
                'offer_price',
                'product_variant_url',
                // meta 
                'about_the_item',
                'product_description',
                'product_variant_embed_video_url',
                'product_components',
                'product_components_ratio_per_gram',
                // meta 
                // services 
                'delivery_charge',
                'free_delivery_upto',
                'delivery_area',
                'replacement_in_days',
                'warranty_in_months',
                'gurantee_in_months',
                'delivery_charge_outside',
                'payment_first',
                'payment_first_amount_in_percentage',
                'payment_first_amount_in_taka',
                'payment_first_delivery_charge'
                // services
            )
            ->leftJoinSub($image, 'image', function ($join) {
                $join->on('product_variants.id', '=', 'image.product_variant_id');
            })
            ->leftJoinSub($product_seller_brand, 'product_seller', function ($join) {
                $join->on('product_variants.product_id', '=', 'product_seller.product_id');
            })
            ->leftJoinSub($service, 'service', function ($join) {
                $join->on('product_variants.id', '=', 'service.product_variant_id');
            })
            ->leftJoinSub($meta, 'meta', function ($join) {
                $join->on('product_variants.id', '=', 'meta.product_variant_id');
            })
            ->whereIn('id', $variant_ids)
            ->get();

        $group_items_by_seller = [];
        foreach ($items as $item) {
            $group_items_by_seller[$item->seller_id][] = $item;
        }
        foreach ($items as $item) {
            foreach ($cart_items as $cart_item) {
                if ($item->variant_id === $cart_item['id']) {
                    $item->quantity = $cart_item['quantity'];
                    $item->price = $item->offer_price
                        ? $item->offer_price
                        : $item->regular_price;
                    if ($item->delivery_area === $shipping_address->district) {
                        $cart_total += $item->quantity *  $item->price;
                    } elseif ($item->delivery_charge_outside) {
                        $cart_total += $item->quantity *  $item->price;
                    }
                }
            }
        }
        foreach ($group_items_by_seller as $key => $group) {
            $total_sale = 0;
            $max_delivery_charge = 0;
            foreach ($group as $item) {
                if ($item->delivery_area === $shipping_address->district || $item->delivery_charge_outside) {
                    $total_sale += $item->quantity * $item->price;
                }
            }
            foreach ($group as $item) {
                if ($total_sale >= $item->free_delivery_upto && $item->free_delivery_upto != 0 && $item->delivery_area === $shipping_address->district) {
                    $item->delivery_charge = 0;
                } else {
                    if ($item->delivery_area != $shipping_address->district) {
                        $item->delivery_charge = $item->delivery_charge_outside;
                    }
                }
                $max_delivery_charge = ($max_delivery_charge < $item->delivery_charge) ? $item->delivery_charge  : $max_delivery_charge;
            }
            $max_delivery_charge_array[$key] = $max_delivery_charge;
        }
        foreach ($max_delivery_charge_array as $key => $charge) {
            $total_delivery_charge += $charge;
        }
        $cart_total_with_delivery = $cart_total + $total_delivery_charge;
        return [
            'group_items_by_seller' => $group_items_by_seller,
            'total_delivery_charge' => $total_delivery_charge,
            'cart_total_with_delivery' => $cart_total_with_delivery,
            'cart_total' => $cart_total,
            'shipping_address' => $shipping_address,
            'max_delivery_charge_array' => $max_delivery_charge_array
        ];
    }
}
