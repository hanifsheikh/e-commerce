<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OfferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('HasPermission:offer-view')->only(['fetchOffers', 'searchOffers']);
        $this->middleware('HasPermission:offer-create')->only('store');
        $this->middleware('HasPermission:offer-update')->only('update');
        $this->middleware('HasPermission:offer-delete')->only('destroy');
    }
    public function fetchOffers()
    {
        $offers = Offer::orderBy('id', 'DESC')->get();
        return response()->json($offers);
    }
    public function searchOffers(Request $request)
    {
        $offers = DB::table('offers')->where('offer_title', 'like', '%' . $request->searchString . '%')->orderBy('id', 'DESC')->get();
        return response()->json($offers);
    }
    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'offer_title' => 'required|unique:offers|max:255',
                'offer_start' => 'required',
                'offer_end' => 'required',
            ],
            [
                'offer_title.required' => 'Offer title is required',
                'offer_start.required' => 'Offer start date is required',
                'offer_end.required' => 'Offer end date is required',
                'offer_title.unique' => 'Offer title has been taken.',
                'offer_title.max' => 'Offer title maximum character exceeded'
            ]
        );
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages(), 'status' => false]);
        }
        $offer_start = Carbon::parse($request->offer_start);
        $offer_end =  Carbon::parse($request->offer_end);
        $offer_duration_in_days = $offer_start->diffInDays($offer_end);
        Offer::create([
            'offer_title' => $request->offer_title,
            'slug' => Str::slug($request->offer_title),
            'offer_start' => $request->offer_start,
            'offer_end' => $request->offer_end,
            'offer_duration_in_days' => $offer_duration_in_days,
        ]);
        return response()->json(['status' => true, 'message' => 'Offer has been created.']);
    }
    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'offer_title' => 'required|max:255',
                'offer_start' => 'required',
                'offer_end' => 'required',
            ],
            [
                'offer_title.required' => 'Offer title is required',
                'offer_start.required' => 'Offer start date is required',
                'offer_end.required' => 'Offer end date is required',
                'offer_title.max' => 'Offer title maximum character exceeded'
            ]
        );
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages(), 'status' => false]);
        }
        $offer_start = Carbon::parse($request->offer_start);
        $offer_end =  Carbon::parse($request->offer_end);
        $offer_duration_in_days = $offer_start->diffInDays($offer_end);
        Offer::where('id', $request->id)->update([
            'offer_title' => $request->offer_title,
            'slug' => Str::slug($request->offer_title),
            'offer_start' => $request->offer_start,
            'offer_end' => $request->offer_end,
            'offer_duration_in_days' => $offer_duration_in_days,
        ]);
        return response()->json(['status' => true, 'message' => 'Offer has been updated.']);
    }
    public function destroy(Request $request)
    {
        $findOffer = Offer::where('id', $request->id)->first();
        if ($findOffer) {
            DB::table('offer_products')->where('offer_id', $findOffer->id)->delete();
            Offer::find($findOffer->id)->delete();
            return response()->json(['status' => true, 'message' => 'Offer has been deleted successfully!']);
        }
        return response()->json(['status' => false, 'message' => 'Offer already deleted!']);
    }
}
