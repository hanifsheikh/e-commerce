<?php

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('HasPermission:payment-view')->only(['fetchTableData', 'fetchSellersWithPaymentData', 'searchSellersWithPaymentData']);
        $this->middleware('HasPermission:payment-create')->only('store');
    }

    public function store(Request $request)
    {
        // ** Store Seller Payment Data **
        DB::table('seller_payments')->insert([
            'seller_id' => $request->seller_id,
            'paid_to_admin' => $request->received_from_seller ? $request->received_from_seller : 0,
            'received_from_admin' => $request->pay_to_seller ? $request->pay_to_seller : 0,
            'payment_of' => Carbon::parse($request->payment_month)->toDateString(),
            'created_at' => Carbon::now()
        ]);

        return response()->json(['status' => true, 'message' => 'Payments record saved successfully.']);
    }
    public function fetchSellersWithPaymentData($month = 0)
    {
        $seller_data_caches =  DB::table('seller_data_caches')->whereMonth(
            'created_at',
            Carbon::now()->subMonth($month)->format('m')
        )->select('total_commission_in_current_month as amount', 'due', 'seller_id');
        $sellers = DB::table('sellers')
            ->select(
                'sellers.*',
                'seller_data_caches.amount',
                'seller_data_caches.due'
            )
            ->leftJoinSub($seller_data_caches, 'seller_data_caches', function ($join) {
                $join->on('sellers.id', '=', 'seller_data_caches.seller_id');
            })
            ->orderBy('id', 'DESC')->paginate(40);
        return response()->json($sellers);
    }
    public function searchSellersWithPaymentData(Request $request)
    {
        $seller_data_caches =  DB::table('seller_data_caches')->whereMonth(
            'created_at',
            Carbon::now()->format('m')
        )->select('total_commission_in_current_month as amount', 'seller_id');
        $sellers = DB::table('sellers')
            ->where('sellers.company_name', 'like', '%' . $request->searchString . '%')
            ->orWhere('sellers.name', '=', $request->searchString)
            ->select(
                'sellers.*',
                'seller_data_caches.amount'
            )
            ->leftJoinSub($seller_data_caches, 'seller_data_caches', function ($join) {
                $join->on('sellers.id', '=', 'seller_data_caches.seller_id');
            })

            ->orderBy('id', 'DESC')->paginate(40);
        return response()->json($sellers);
    }
    public function fetchTableData(Request $request)
    {
        $total_commission_in_current_month = DB::table('seller_data_caches')->where('seller_id', $request->id)->whereMonth(
            'created_at',
            Carbon::now()->subMonth($request->month)->format('m')
        )->select('total_commission_in_current_month as amount')->first();
        $entries = DB::table('seller_payments')->whereMonth('payment_of', Carbon::now()->subMonth($request->month)->format('m'))->where('seller_id', $request->id)->orderBy('id', 'DESC')->get();

        return  response()->json(['total_commission' => $total_commission_in_current_month, 'entries' => $entries]);
    }
}
