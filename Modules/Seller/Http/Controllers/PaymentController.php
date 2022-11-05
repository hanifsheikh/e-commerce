<?php

namespace Modules\Seller\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:seller');
    }

    public function fetchTableData(Request $request)
    {
        if (!Auth::user()) {
            return abort(401);
        }
        $total_commission_in_current_month = DB::table('seller_data_caches')->where('seller_id', Auth::id())->whereMonth(
            'created_at',
            Carbon::now()->subMonth($request->month)->format('m')
        )->select('total_commission_in_current_month as amount')->first();
        $entries = DB::table('seller_payments')->whereMonth('payment_of', Carbon::now()->subMonth($request->month)->format('m'))->where('seller_id', Auth::id())->orderBy('id', 'DESC')->get();

        return  response()->json(['total_commission' => $total_commission_in_current_month, 'entries' => $entries]);
    }
}
