<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\SellerCollection;
use App\Models\Product;
use Illuminate\Support\Facades\Response as FacadeResponse;
use App\Models\ProductVariant;
use App\Models\Seller;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SellerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('HasPermission:seller-view')->only(['index', 'searchSellers', 'download', 'getSellerPhoto']);
        $this->middleware('HasPermission:seller-update')->only(['update', 'ban', 'unban', 'approve', 'decline']);
    }
    public function index()
    {
        $sellers = DB::table('sellers')
            ->leftJoin('seller_documents', 'sellers.id', '=', 'seller_documents.seller_id')
            ->select(['sellers.*', 'seller_documents.nid', 'seller_documents.trade_license', 'seller_documents.electricity_bill'])
            ->orderBy('id', 'DESC')->paginate(40);
        return new SellerCollection($sellers);
    }
    public function getSellerPhoto($id)
    {
        if (!Auth::user()) {
            return abort(401);
        }
        $avatar = Seller::where('id', $id)->select('avatar')->first()->avatar;
        $path = storage_path('app/private/seller_images/' . $avatar);
        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = FacadeResponse::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }

    public function searchSellers(Request $request)
    {
        $sellers = Seller::where('name', 'like', '%' . $request->searchString . '%')->orWhere('company_name', 'like', '%' . $request->searchString . '%')->orWhere('email',  '=', $request->searchString)->orWhere('contact_no',  '=', $request->searchString)->orWhere('alternative_contact_no',  '=', $request->searchString)->orderBy('id', 'DESC')->paginate(40);
        return new SellerCollection($sellers);
    }
    public function update(Request $request)
    {
        $seller = Seller::find($request->seller_id);
        return response()->json(['message' => "Seller <b>" . $seller->name . "</b>'s has been updated.", "status" => true]);
    }
    public function ban(Request $request)
    {
        $seller = Seller::find($request->id);
        $seller->update([
            'active' => 0
        ]);
        $seller->tokens()->delete();
        ProductVariant::where('seller_id', $seller->id)->update([
            'active' => false
        ]);
        Product::where('seller_id', $seller->id)->update([
            'active' => false
        ]);
        return response()->json(['message' => "Seller <b>" . $seller->name . "</b> has been banned!", "status" => true]);
    }
    public function banProducts(Request $request)
    {
        $seller = Seller::find($request->id);
        $seller->update([
            'is_product_banned' => 1
        ]);
        ProductVariant::where('seller_id', $seller->id)->update([
            'active' => false
        ]);
        Product::where('seller_id', $seller->id)->update([
            'active' => false
        ]);
        return response()->json(['message' => "Seller <b>" . $seller->name . "</b>'s products has been banned!", "status" => true]);
    }
    public function unban(Request $request)
    {
        $seller = Seller::find($request->id);
        $seller->update([
            'active' => 1,
            'is_product_banned' => 0
        ]);
        ProductVariant::where('seller_id', $seller->id)->update([
            'active' => 1
        ]);
        Product::where('seller_id', $seller->id)->update([
            'active' => 1
        ]);
        return response()->json(['message' => "Seller <b>" . $seller->name . "</b>'s ban has been removed!", "status" => true]);
    }
    public function destroy(Request $request)
    {
        $seller = Seller::find($request->id);
        if ($seller) {
            $haveProducts = Product::where('seller_id', $seller->id)->first();
            if ($haveProducts) {
                return response()->json(['message' =>  "Seller have products.", "status" => false]);
            }
            $seller_name = $seller->name;
            $seller->delete();
            return response()->json(['message' => "Seller <b>" . $seller_name . "</b> has been deleted.", "status" => true]);
        }
        return response()->json(['message' =>  "Already deleted.", "status" => false]);
    }
    public function approve(Request $request)
    {
        $seller = Seller::find($request->id);
        $seller->update([
            'documents_approved_at' => Carbon::now()
        ]);
        return response()->json(['message' => "Seller <b>" . $seller->name . "</b> has been approved!", "status" => true]);
    }
    public function decline(Request $request)
    {
        $seller = Seller::find($request->id);
        $seller->update([
            'documents_submitted_at' => null,
            'documents_approved_at' => null,
            'documents_declined_at' => Carbon::now(),
        ]);
        DB::table('seller_documents')->where('seller_id', $request->id)->delete();
        return response()->json(['message' => "Seller <b>" . $seller->name . "</b> has been declined!", "status" => true]);
    }
    public function download($company, $file)
    {
        $path = storage_path('app/private/seller_documents/' . $company . '/' . $file);
        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = FacadeResponse::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }
}
