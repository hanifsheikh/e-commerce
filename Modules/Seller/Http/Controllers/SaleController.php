<?php

namespace Modules\Seller\Http\Controllers;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:seller');
    }
    public function fetchData()
    {
        $currentMonthData = DB::table('order_products')->where('seller_id', Auth::id())->whereMonth(
            'created_at',
            Carbon::now()->format('m')
        )->where('item_received', 1)->orderBy('created_at')->get(['total_price', 'created_at']);
        $saleItems = DB::table('order_products')
            ->join('order_shipping_details', 'order_products.order_id', '=', 'order_shipping_details.order_id')
            ->select(
                'order_products.id',
                'order_products.created_at',
                'order_products.image',
                'order_products.product_title',
                'order_products.product_variant_title',
                'order_products.sku',
                'order_products.status',
                'order_products.price',
                'order_products.quantity',
                'order_products.commission',
                'order_products.created_at',
                'order_shipping_details.area',
            )
            ->where('seller_id', Auth::id())
            ->whereMonth(
                'order_products.created_at',
                Carbon::now()->format('m')
            )->orderBy('created_at', 'DESC')->get();

        foreach ($currentMonthData as $data) {
            $dt = new DateTime($data->created_at);
            $data->created_at = $dt->format('d-M');
        }
        return response()->json(['data' => $currentMonthData, 'sale_items' => $saleItems]);
    }
    public function fetchTableData(Request $request)
    {
        $saleItems = [];
        $graphData = [];
        if ($request->limit["value"] === 30) {
            $graphData = DB::table('order_products')->whereMonth(
                'created_at',
                Carbon::now()->format('m')
            )->where('seller_id', Auth::id())->where('item_received', 1)->orderBy('created_at')->get(['total_price', 'created_at']);
            foreach ($graphData as $data) {
                $dt = new DateTime($data->created_at);
                $data->created_at = $dt->format('d-M');
            }
            $saleItems = DB::table('order_products')->join('order_shipping_details', 'order_products.order_id', '=', 'order_shipping_details.order_id')
                ->select(
                    'order_products.id',
                    'order_products.created_at',
                    'order_products.image',
                    'order_products.product_title',
                    'order_products.product_variant_title',
                    'order_products.sku',
                    'order_products.status',
                    'order_products.price',
                    'order_products.quantity',
                    'order_products.commission',
                    'order_products.created_at',
                    'order_shipping_details.area',
                )
                ->whereMonth(
                    'order_products.created_at',
                    Carbon::now()->format('m')
                )->where('seller_id', Auth::id())->orderBy('created_at', 'DESC')->get();
        } elseif ($request->limit["value"] === 7) {
            $date = Carbon::now()->subDays(7);

            $graphData = DB::table('order_products')->where(
                'order_products.created_at',
                '>=',
                $date
            )->where('seller_id', Auth::id())->where('item_received', 1)->orderBy('created_at')->get(['total_price', 'created_at']);
            foreach ($graphData as $data) {
                $dt = new DateTime($data->created_at);
                $data->created_at = $dt->format('d-M');
            }
            $saleItems = DB::table('order_products')->join('order_shipping_details', 'order_products.order_id', '=', 'order_shipping_details.order_id')
                ->select(
                    'order_products.id',
                    'order_products.created_at',
                    'order_products.image',
                    'order_products.product_title',
                    'order_products.product_variant_title',
                    'order_products.sku',
                    'order_products.status',
                    'order_products.price',
                    'order_products.quantity',
                    'order_products.commission',
                    'order_products.created_at',
                    'order_shipping_details.area',
                )
                ->where(
                    'order_products.created_at',
                    '>=',
                    $date
                )->where('seller_id', Auth::id())->orderBy('created_at', 'DESC')->get();
        } elseif ($request->limit["value"] === -30) {
            $date = Carbon::now()->subMonth()->format('m');

            $graphData = DB::table('order_products')->whereMonth(
                'order_products.created_at',
                '=',
                $date
            )->where('seller_id', Auth::id())->where('item_received', 1)->orderBy('created_at')->get(['total_price', 'created_at']);
            foreach ($graphData as $data) {
                $dt = new DateTime($data->created_at);
                $data->created_at = $dt->format('d-M');
            }
            $saleItems = DB::table('order_products')->join('order_shipping_details', 'order_products.order_id', '=', 'order_shipping_details.order_id')
                ->select(
                    'order_products.id',
                    'order_products.created_at',
                    'order_products.image',
                    'order_products.product_title',
                    'order_products.product_variant_title',
                    'order_products.sku',
                    'order_products.status',
                    'order_products.price',
                    'order_products.quantity',
                    'order_products.commission',
                    'order_products.created_at',
                    'order_shipping_details.area',
                )
                ->whereMonth(
                    'order_products.created_at',
                    '=',
                    $date
                )->where('seller_id', Auth::id())->orderBy('created_at', 'DESC')->get();
        } elseif ($request->limit["value"] === 365) {
            $date = Carbon::now()->format('Y');

            $graphData = DB::table('order_products')->whereYear(
                'order_products.created_at',
                '=',
                $date
            )->where('seller_id', Auth::id())->where('item_received', 1)->orderBy('created_at')->get(['total_price', 'created_at']);
            foreach ($graphData as $data) {
                $dt = new DateTime($data->created_at);
                $data->created_at = $dt->format('d-M');
            }
            $saleItems = DB::table('order_products')->join('order_shipping_details', 'order_products.order_id', '=', 'order_shipping_details.order_id')
                ->select(
                    'order_products.id',
                    'order_products.created_at',
                    'order_products.image',
                    'order_products.product_title',
                    'order_products.product_variant_title',
                    'order_products.sku',
                    'order_products.status',
                    'order_products.price',
                    'order_products.quantity',
                    'order_products.commission',
                    'order_products.created_at',
                    'order_shipping_details.area',
                )
                ->whereYear(
                    'order_products.created_at',
                    '=',
                    $date
                )->where('seller_id', Auth::id())->orderBy('created_at', 'DESC')->get();
        } else {
            $date = Carbon::now('Asia/Dhaka');
            $graphData = DB::table('order_products')->whereDate(
                'order_products.created_at',
                '>=',
                $date
            )->where('seller_id', Auth::id())->where('item_received', 1)->orderBy('created_at')->get(['total_price', 'created_at']);
            foreach ($graphData as $data) {
                $dt = new DateTime($data->created_at);
                $data->created_at = $dt->format('d-M');
            }
            $saleItems = DB::table('order_products')->join('order_shipping_details', 'order_products.order_id', '=', 'order_shipping_details.order_id')
                ->select(
                    'order_products.id',
                    'order_products.created_at',
                    'order_products.image',
                    'order_products.product_title',
                    'order_products.product_variant_title',
                    'order_products.sku',
                    'order_products.status',
                    'order_products.price',
                    'order_products.quantity',
                    'order_products.commission',
                    'order_products.created_at',
                    'order_shipping_details.area',
                )
                ->whereDate(
                    'order_products.created_at',
                    '>=',
                    $date
                )->where('seller_id', Auth::id())->orderBy('created_at', 'DESC')->get();
        }

        return response()->json(['sale_items' => $saleItems, 'graphData' => $graphData]);
    }
}
