<?php

namespace Modules\Seller\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CounterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:seller');
    }
    public function unread_counter()
    {
        $order_counter = $this->unread_orders();
        $unread_sales = 0;
        return response()->json([
            'unread_orders' => $order_counter,
            'unread_sales' => $unread_sales
        ]);
    }
    private function unread_orders()
    {
        $order_seen = DB::table('order_seen')
            ->select('order_id', 'user_id')
            ->where('user_id', '=', Auth::id());

        $unseen_orders = DB::table('orders')
            ->select('orders.id', 'order_seen.user_id', 'order_seen.order_id')
            ->leftJoinSub($order_seen, 'order_seen', function ($join) {
                $join->on('orders.id', '=', 'order_seen.order_id');
            })
            ->join('order_sellers', 'orders.id', '=', 'order_sellers.order_id')
            ->where('order_sellers.seller_id', Auth::id())
            ->whereNull('order_seen.user_id')
            ->count();

        return $unseen_orders;
    }
}
