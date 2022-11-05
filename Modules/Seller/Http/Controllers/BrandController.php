<?php

namespace Modules\Seller\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:seller');
    }

    public function fetchBrands()
    {
        if (!Auth::user()) {
            return abort(401);
        }
        $brands = DB::table('brands')->orderBy('id', 'DESC')->get();
        return response()->json($brands);
    }
}
