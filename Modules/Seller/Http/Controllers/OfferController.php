<?php

namespace Modules\Seller\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\Offer;

class OfferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:seller');
    }
    public function fetchOffers()
    {
        $offers = Offer::orderBy('id', 'DESC')->get();
        return response()->json($offers);
    }
}
