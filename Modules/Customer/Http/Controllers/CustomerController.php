<?php

namespace Modules\Customer\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    public function orders()
    {
        $order_shipping_address = DB::table('order_shipping_details')
            ->select('order_id', 'receiver_name', 'receiver_contact_no', 'address', 'district')
            ->where('customer_id', Auth::id());

        $orders = DB::table('orders')
            ->select('orders.id', 'orders.order_no', 'cart_total', 'order_id', 'customer_id', 'receiver_name', 'receiver_contact_no', 'address', 'district', 'orders.created_at')
            ->leftJoinSub($order_shipping_address, 'order_shipping_address', function ($join) {
                $join->on('orders.id', '=', 'order_shipping_address.order_id');
            })
            ->orderBy('id', "DESC")
            ->where('customer_id', Auth::id())
            ->paginate(100);
        return view('customer::layouts.orders', compact('orders'));
    }
    public function wishlist()
    {
        return view('customer::layouts.wishlist');
    }
}
