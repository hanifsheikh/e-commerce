<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Resources\CustomerCollection;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('HasPermission:customer-view')->only(['index', 'searchCustomers']);
        $this->middleware('HasPermission:customer-update')->only('update');
        $this->middleware('HasPermission:customer-delete')->only('destroy');
    }

    public function index()
    {
        $customers = Customer::orderBy('id', 'DESC')->paginate(40);
        $customerResource = new CustomerCollection($customers);
        return $customerResource;
    }
    public function searchCustomers(Request $request)
    {
        $customers = Customer::where('name', 'like', '%' . $request->searchString . '%')
            ->orWhere('email', '=', $request->searchString)
            ->orderBy('id', 'DESC')->paginate(40);
        $customerResource = new CustomerCollection($customers);
        return $customerResource;
    }

    public function update(Request $request)
    {
        $customer = Customer::find($request->customer_id);
        return response()->json(['message' => "Customer <b>" . $customer->name . "</b>'s has been updated.", "status" => true]);
    }
    public function destroy(Request $request)
    {
        $customer = Customer::find($request->id);
        if ($customer) {
            $customer_have_orders = DB::table('order_products')->where('customer_id', $customer->id)->first();
            if ($customer_have_orders) {
                return response()->json(['message' =>  "Customer have order data.", "status" => false]);
            }
            $customer_name = $customer->name;
            $customer->delete();
            return response()->json(['message' => "Customer <b>" . $customer_name . "</b> has been deleted.", "status" => true]);
        }
        return response()->json(['message' =>  "Already deleted.", "status" => false]);
    }
}
