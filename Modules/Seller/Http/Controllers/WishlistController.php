<?php

namespace Modules\Seller\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    public function index()
    {
        $products = DB::table('product_wishlists')->where('seller_id', Auth::id())
            ->select(
                'id',
                'product_id',
                'product_title',
                'image',
                DB::raw('SUM(quantity) as quantity')
            )
            ->groupBy('product_id')
            ->orderBy('id', 'DESC')
            ->get();
        return response()->json($products);
    }
    public function showVariants(Request $request)
    {

        $products = DB::table('product_wishlists')
            ->join('customers', 'product_wishlists.customer_id', '=', 'customers.id')
            ->select('product_wishlists.*', 'customers.id as customer_id', 'customers.name as customer_name', 'customers.avatar as customer_avatar')
            ->where('product_wishlists.product_id', $request->product_id)->where('product_wishlists.seller_id', Auth::id())->orderBy('product_wishlists.id', 'DESC')->get();

        return response()->json($products);
    }
}
