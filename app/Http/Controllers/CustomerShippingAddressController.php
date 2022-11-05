<?php

namespace App\Http\Controllers;

use App\Models\CategoryCache;
use App\Models\CustomerShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerShippingAddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    public function shippingAddress()
    {
        $customer =  Auth::user();
        $categories = DB::table('home_page_caches')->select('category_caches')->first();
        $categories =  json_decode($categories->category_caches);
        $addresses = CustomerShippingAddress::where('customer_id', Auth::id())->orderBy('id', 'DESC')->get();
        return view('shipping-address', compact(['categories', 'customer']))->with([
            'addresses' => $addresses
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'receiver_name' => 'required|max:255',
                'receiver_address' => 'required',
                'receiver_contact_no' => 'required|regex:/(01)[0-9]{9}/',
                'area' => 'required',
                'district' => 'required'
            ],
            [
                'receiver_name.required' => 'Name is requried.',
                'receiver_name.max' => 'Provide a valid name.',
                'district.required' => 'District is required.',
                'area.required' => 'Area is required.',
                'receiver_address.required' => 'Address is required.',
                'receiver_contact_no.required' => 'Contact number is required.',
                'receiver_contact_no.regex' => 'Provide a valid contact number.'
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        CustomerShippingAddress::create([
            'customer_id' => Auth::id(),
            'receiver_name' => $request->receiver_name,
            'receiver_address' => $request->receiver_address,
            'receiver_contact_no' => $request->receiver_contact_no,
            'area' => $request->area,
            'district' => $request->district,
            'label' => $request->label,
        ]);
        $addresses = CustomerShippingAddress::where('customer_id', Auth::id())->get();
        return redirect()->back()->with('addresses', $addresses);
    }
    public function destroy(Request $request)
    {
        CustomerShippingAddress::where('id', $request->id)->where('customer_id', Auth::id())->delete();
        return redirect()->back();
    }
}
