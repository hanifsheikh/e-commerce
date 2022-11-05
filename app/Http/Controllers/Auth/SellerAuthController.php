<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\VerifyEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Http\Resources\Auth\AuthSellerCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Seller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class SellerAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:seller')->except(['login', 'register', 'verify', 'logout']);
        $this->middleware('auth:sellerinactive')->only(['logout', 'uploadDocuments']);
    }
    public function login(Request $request)
    {
        return $this->authenticate($request);
    }
    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:255',
                'email' => "required|unique:sellers|email",
                'contact_no' => 'required|unique:sellers|digits_between:9,11',
                'alternative_contact_no' => 'nullable|digits_between:9,11',
                'company_name' => 'required|unique:sellers|max:255',
                'address' => 'required|max:255',
                'company_address' => 'required|max:255',
                'selling_products' => 'required|max:255',
                'password' => 'required|confirmed|min:8',
            ],
            [
                'name.required' => 'Seller name is required.',
                'name.max' => 'Seller name maximum character exceeded.',
                'email.required' => 'Email address is required.',
                'email.unique' => 'Email address already taken.',
                'email.email' => 'Email address is invalid.',
                'contact_no.required' => 'Contact number is required.',
                'contact_no.unique' => 'Contact number already taken.',
                'contact_no.digits_between' => 'Contact number is invalid.',
                'alternative_contact_no.digits_between' => 'Alternative contact number is invalid.',
                'company_name.required' => 'Company name is required.',
                'company_name.unique' => 'Company name already taken.',
                'company_name.max' => 'Company name maximum character exceeded.',
                'address.required' => 'Seller address is required.',
                'company_address.required' => 'Company address is required.',
                'password.required' => 'Password is required',
                'password.confirmed' => 'Password confirmation is mismatch.',
                'password.min' => 'Password must be at least 8 characters.',
                'selling_products.required' => 'Selling Products field is required',
                'selling_products.max' => 'Selling Products field maximum character exceeded',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages(), 'status' => false]);
        }
        $seller = Seller::create([
            "name" => $request->name,
            "email" => $request->email,
            "owner_address" => $request->address,
            "company_address" => $request->company_address,
            "selling_products" => $request->selling_products,
            "contact_no" => $request->contact_no,
            "company_name" => $request->company_name,
            "alternative_contact_no" => $request->alternative_contact_no,
            "password" => bcrypt($request->password),
        ]);

        if ($seller) {
            $verifyemail = VerifyEmail::create([
                'hash' => Str::random(60),
                'type' => 'seller',
                'type_id' => $seller->id
            ]);
            $this->sendmail($request, $verifyemail->hash);
            return response()->json(['message' => 'Registration successfull.', 'status' => true]);
        }
        return response()->json(['message' => 'Something went wrong!', 'status' => false]);
    }
    public function sendmail(Request $request, $hash)
    {
        $headers = ["email" => $request->email, "name" => $request->name, "subject" => env('APP_NAME') . ' Email Verification', "verification_link" => env('APP_URL') . 'seller/verify_email/' . $hash];
        // $headers = ["email" => "hanifsheikh@gmail.com", "name" => "Hanif Sheikh", "subject" => "I need a portfolio site", "message_body" => "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Temporibus doloremque reprehenderit quidem ratione iusto culpa nisi quis nostrum sapiente omnis enim minus magnam deleniti alias quod unde, obcaecati totam quisquam."];
        $user = ["to" => $request->email];
        try {
            Mail::send('mail', $headers, function ($message) use ($headers, $user) {
                $message->to($user['to']);
                $message->subject($headers['subject']);
            });
        } catch (Exception $exception) {
            Log::channel('single')->info($exception->getMessage());
            return response()->json(['failureMessage' => "Something went wrong!"]);
        }
        if (Mail::failures()) {
            return response()->json(['failureMessage' => "Something went wrong!"]);
        }
        return response()->json(['successMessage' => "Email has been sent successfully!"]);
    }
    public function verify($hash)
    {
        if (Str::length($hash) !== 60) {
            return 'Invalid token url';
        }
        $verifyemail = VerifyEmail::where('hash', $hash)->first();
        if ($verifyemail) {
            Seller::find($verifyemail->type_id)->update([
                'email_verified_at' => Carbon::now(),
                'active' => 1
            ]);
            $verifyemail->delete();
            return redirect('/seller/login');
        } else {
            return 'token expired';
        }
    }
    public function authenticate($request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $banned_user = Seller::where('email', $request->email)->where('active', false)->whereNotNull('email_verified_at')->first();
        $not_verified_email = Seller::where('email', $request->email)->where('active', true)->whereNull('email_verified_at')->first();
        $have_account = Seller::where('email', $request->email)->first();
        if ($have_account) {
            if ($banned_user) {
                return response()->json([
                    'message' => 'Your account has been suspended!'
                ], 403);
            } else if ($not_verified_email) {
                return response()->json([
                    'message' => 'Your account email is not verified yet!'
                ], 403);
            }
        } else {
            return response()->json([
                'message' => 'No account found for this email.'
            ], 403);
        }

        $user = Seller::where('email', $request->email)->where('active', true)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {

            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 403);
        }
        $responseData = [
            'user' => $user,
            'token' => $user->createToken($request->email)->plainTextToken
        ];
        return new AuthSellerCollection($responseData);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged Out!'
        ], 200);
    }
    public function changeTheme(Request $request)
    {
        Seller::where('id', Auth::id())->update([
            'theme' => $request->theme
        ]);
    }
    public function updateSettings(Request $request)
    {
        $user = Seller::where('id', Auth::id())->first();
        if (Hash::check($request->current_password, $user->password)) {
            Seller::where('id', Auth::id())->update([
                "name" => $request->name,
                'password' => bcrypt($request->new_password)
            ]);
            return response()->json(['status' => true, 'message' => "Password updated"]);
        } else {
            return response()->json(['status' => false, 'message' => "Password wrong!"]);
        }
    }
    public function updateSellerSettingsWithoutPassword(Request $request)
    {
        Seller::where('id', Auth::id())->update(['name' => $request->name]);
        return response()->json(['status' => true, 'message' => "Information updated"]);
    }
    public function uploadDocuments(Request $request)
    {
        $sellerDocumentsFolder = 'seller_documents/';

        if (!file_exists(storage_path('app/private/' . $sellerDocumentsFolder))) {
            mkdir(storage_path('app/private/' . $sellerDocumentsFolder), 755, true);
        }
        $nid =  'NID - ' . Auth::user()->name . ' - ' . Auth::user()->company_name . '.' . $request->file('nid')->extension();
        if ($request->file('trade_license')) {
            $trade_license =  'Trade License - ' . Auth::user()->name . ' - ' . Auth::user()->company_name . '.' . $request->file('trade_license')->extension();
            $request->file('trade_license')->move(storage_path('app/private/' . $sellerDocumentsFolder .  Auth::user()->company_name . '/'), $trade_license);
        }

        $electricity_bill =  'Electricity Bill - ' . Auth::user()->name . ' - ' . Auth::user()->company_name . '.' . $request->file('electricity_bill')->extension();

        $request->file('nid')->move(storage_path('app/private/' . $sellerDocumentsFolder .  Auth::user()->company_name . '/'), $nid);
        $request->file('electricity_bill')->move(storage_path('app/private/' . $sellerDocumentsFolder .  Auth::user()->company_name . '/'), $electricity_bill);

        Seller::where('id', Auth::id())->update([
            'documents_submitted_at' => Carbon::now(),
            'url' => $request->url
        ]);

        if ($request->file('trade_license')) {

            DB::table('seller_documents')->insert([
                'seller_id' => Auth::id(),
                'nid' => Auth::user()->company_name . '/' . $nid,
                'trade_license' => Auth::user()->company_name . '/' . $trade_license,
                'electricity_bill' => Auth::user()->company_name . '/' . $electricity_bill
            ]);
        } else {
            DB::table('seller_documents')->insert([
                'seller_id' => Auth::id(),
                'nid' => Auth::user()->company_name . '/' . $nid,
                'electricity_bill' => Auth::user()->company_name . '/' . $electricity_bill
            ]);
        }
        return response()->json(['message' => 'Documents uploaded successfully.', 'status' => true]);
    }
    public function getSellerPhoto()
    {
        if (!Auth::user()) {
            return abort(401);
        }
        $path = storage_path('app/private/seller_images/' . Auth::user()->avatar);
        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = FacadeResponse::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }
    public function get_token_count(Request $request)
    {
        $total_count = $request->user()->tokens()->count();
        return response()->json($total_count - 1);
    }
    public function logoutAllSession(Request $request)
    {
        // Get current token ID
        $tokenId = $request->user()->currentAccessToken()->id;

        // Count tokens.
        $total_tokens = $request->user()->tokens()->count();

        // Remove All token except current one.
        $request->user()->tokens()->where('id', '!=', $tokenId)->delete();

        $message  = 'Logged out from ' . $total_tokens - 1 . ' other active logins.';
        return response()->json([
            'message' => $message
        ]);
        // $user->tokens()->where('id', $tokenId)->delete();
    }
    public function uploadAvatar(Request $request)
    {
        $imagesFolder = 'seller_images/';
        if (!file_exists(storage_path('app/private/' . $imagesFolder))) {
            mkdir(storage_path('app/private/' . $imagesFolder), 755, true);
        }
        $avatar = $request->file('avatar');
        if ($avatar) {
            $avatar_name = Auth::user()->name . '_' . time() .  '.webp';
            $saveAvatar = $this->resizeAvatar($avatar, $avatar_name);
            if ($saveAvatar) {
                Seller::where('id', Auth::id())->update([
                    'avatar' => $avatar_name
                ]);
                if (Auth::user()->avatar != 'avatar_default.jpg') {
                    if (file_exists(storage_path('app/private/seller_images/' . Auth::user()->avatar))) {
                        unlink(storage_path('app/private/seller_images/' . Auth::user()->avatar));
                    }
                }
            }
            return response()->json(['status' => true, 'message' => 'Profile avatar updated!']);
        }
    }
    public function resizeAvatar($file, $fileNameToStore)
    {
        // Resize image
        $resizedContent = Image::make($file)->orientate()->fit(540, 540)->encode('webp', 100);
        // Put image to storage        
        $save = Storage::disk('private')->put('seller_images/' . $fileNameToStore, $resizedContent->__toString());
        if ($save) {
            return true;
        }
        return false;
    }


    public function uploadStoreLogo(Request $request)
    {
        $imagesFolder = 'store_profile_images/';
        if (!file_exists(storage_path('app/public/' . $imagesFolder))) {
            mkdir(storage_path('app/public/' . $imagesFolder), 755, true);
        }
        $logo = $request->file('logo');
        if ($logo) {
            $logo_name = Str::slug(Auth::user()->company_name, '_') . '_' . time() .  '.webp';
            $saveLogo = $this->resizeLogo($logo, $logo_name);
            if ($saveLogo) {
                Seller::where('id', Auth::id())->update([
                    'logo' => $logo_name
                ]);
                if (Auth::user()->logo != 'no_image.png') {
                    if (file_exists(storage_path('app/public/store_profile_images/' . Auth::user()->logo))) {
                        unlink(storage_path('app/public/store_profile_images/' . Auth::user()->logo));
                    }
                }
            }
            return response()->json(['status' => true, 'logo' => $logo_name, 'message' => 'Shop Logo updated!']);
        }
    }
    public function resizeLogo($file, $fileNameToStore)
    {
        // Resize image
        $resizedContent = Image::make($file)->orientate()->fit(360, 360)->encode('webp', 100);
        // Put image to storage        
        $save = Storage::disk('public')->put('store_profile_images/' . $fileNameToStore, $resizedContent->__toString());
        if ($save) {
            return true;
        }
        return false;
    }
    public function uploadStoreBanner(Request $request)
    {
        $imagesFolder = 'store_banner_images/';
        if (!file_exists(storage_path('app/public/' . $imagesFolder))) {
            mkdir(storage_path('app/public/' . $imagesFolder), 755, true);
        }
        $banner = $request->file('banner');
        if ($banner) {
            $banner_name = Str::slug(Auth::user()->company_name, '_') . '_' . time() .  '.webp';
            $saveBanner = $this->resizeBanner($banner, $banner_name);
            if ($saveBanner) {
                Seller::where('id', Auth::id())->update([
                    'banner' => $banner_name
                ]);
                if (Auth::user()->banner != 'banner_default.jpg') {
                    if (file_exists(storage_path('app/public/store_profile_images/' . Auth::user()->banner))) {
                        unlink(storage_path('app/public/store_profile_images/' . Auth::user()->banner));
                    }
                }
            }
            return response()->json(['status' => true, 'banner' => $banner_name, 'message' => 'Shop Banner updated!']);
        }
    }
    public function resizeBanner($file, $fileNameToStore)
    {
        // Resize image
        $resizedContent = Image::make($file)->orientate()->fit(1280, 420)->encode('webp', 100);
        // Put image to storage        
        $save = Storage::disk('public')->put('store_banner_images/' . $fileNameToStore, $resizedContent->__toString());
        if ($save) {
            return true;
        }
        return false;
    }
}
