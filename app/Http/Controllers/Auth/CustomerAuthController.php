<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use App\Models\VerifyEmail;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class CustomerAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer')->only(['home']);
    }
    public function email_sent_notice()
    {
        return view('customer::notice');
    }
    public function home()
    {
        return redirect()->route('home-page');
    }

    public function sendmail(Request $request, $hash)
    {
        $headers = ["email" => $request->email, "name" => $request->name, "subject" => env('APP_NAME') . ' Email Verification', "verification_link" => env('APP_URL') . 'customer/verify_email/' . $hash];
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
    public function register(Request $request)
    {
        $customer_exist = Customer::where('contact', $request->contact)->first();

        if ($customer_exist && Hash::check($request->password, $customer_exist->password)) {
            return redirect()->back()->withInput()->withErrors(['Account exist, try login']);
        }
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:255',
                'contact' => 'required',
                'password' => ['required', 'confirmed', Password::min(8)],
                'agree' => 'accepted',
            ],
            [
                'name.required' => 'Name is requried.',
                'name.max' => 'Provide a valid name.',
                'contact.required' => 'Contact no. is required.',
                'password' => 'Password is Required.',
                'password.confirmed' => "Password didn't matched.",
                'password.min' => "Password must be at least 8 digit.",
                'agree.accepted' => "You must have to agree with our terms and conditions.",
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        Customer::create([
            'name' => $request->name,
            'contact' => $request->contact,
            'password' => Hash::make($request->password),
        ]);


        $customers = Customer::where('contact', $request->contact)->get();

        foreach ($customers as $check_customer) {
            if (Hash::check($request->password, $check_customer->password)) {
                Auth::guard('customer')->login($check_customer);
                $request->session()->regenerate();
                return redirect()->intended();
            }
        }
        return redirect(route('home-page'));
    }
    public function verify($hash)
    {
        if (Str::length($hash) !== 60) {
            return 'Invalid token url';
        }
        $verifyemail = VerifyEmail::where('hash', $hash)->first();
        if ($verifyemail) {
            Customer::find($verifyemail->type_id)->update([
                'email_verified_at' => Carbon::now(),
            ]);
            $verifyemail->delete();
            $message = 'You have successfully created your account. Try login with your credentials.';
            return redirect('/customer/login')->with('message', $message);
        } else {
            return 'token expired';
        }
    }

    public function login(Request $request)
    {
        return $this->authenticate($request);
    }
    public function authenticate(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'contact' => 'required',
                'password' => ['required',  Password::min(8)],
            ],
            [
                'contact.required' => 'Mobile no. is required.',
                'password' => 'Password is Required.',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $customers = Customer::where('contact', $request->contact)->get();

        if (count($customers)) {
            foreach ($customers as $check_customer) {
                if (Hash::check($request->password, $check_customer->password)) {
                    Auth::guard('customer')->login($check_customer);
                    $request->session()->regenerate();
                    return redirect()->intended();
                }
            }
            return back()->withInput()->withErrors([
                'password_not_matched' => 'Password did not matched',
            ]);
        } else {
            return back()->withInput()->withErrors([
                'not_found' => 'Account not found',
            ]);
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function facebookRedirect()
    {
        return Socialite::driver('facebook')->redirect();
    }
    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }
    public function loginWithGoogle()
    {
        try {

            $user = Socialite::driver('google')->user();
            $isUser = Customer::where('email', $user->email)->first();

            if ($isUser) {
                Auth::guard('customer')->login($isUser);
                return redirect()->route('home-page');
            } else {
                $createUser = Customer::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'provider' => 'google',
                    'email_verified_at' => now(),
                    'provider_id' => $user->id,
                    'avatar' => $user->avatar,
                ]);
                Auth::guard('customer')->login($createUser);
                return redirect()->route('home-page');
            }
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }
    public function loginWithFacebook()
    {
        try {

            $user = Socialite::driver('facebook')->user();
            $isUser = Customer::where('email', $user->email)->first();

            if ($isUser) {
                Auth::guard('customer')->login($isUser);
                return redirect()->route('home-page');
            } else {
                $createUser = Customer::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'provider' => 'facebook',
                    'provider_id' => $user->id,
                    'email_verified_at' => now(),
                    'avatar' => $user->avatar_original,
                ]);

                Auth::guard('customer')->login($createUser);
                return redirect()->route('home-page');
            }
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }
    public function forgot_password()
    {
        return view('customer::forgot_password');
    }
}
