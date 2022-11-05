<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Auth\AuthUserCollection;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class AdminAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['login']);
    }

    public function login(Request $request)
    {
        return $this->authenticate($request);
    }
    public function authenticate($request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 403);
        }
        $responseData = [
            'user' => $user,
            'token' => $user->createToken($request->email)->plainTextToken
        ];
        return new AuthUserCollection($responseData);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => "Logged Out!"
        ], 200);
    }
    public function changeTheme(Request $request)
    {
        User::where('id', Auth::id())->update([
            'theme' => $request->theme
        ]);
    }
    public function getAdminPhoto()
    {       
        if (!Auth::user()) {
            return abort(401);
        }
        $path = storage_path('app/private/admin_images/' . Auth::user()->avatar);
        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = FacadeResponse::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }
    public function updateSettings(Request $request)
    {
        $user = User::where('id', Auth::id())->first();
        if (Hash::check($request->current_password, $user->password)) {
            User::where('id', Auth::id())->update([
                "name" => $request->name,
                'password' => bcrypt($request->new_password)
            ]);
            return response()->json(['status' => true, 'message' => "Password updated"]);
        } else {
            return response()->json(['status' => false, 'message' => "Password wrong!"]);
        }
    }
    public function updateAdminSettingsWithoutPassword(Request $request)
    {
        User::where('id', Auth::id())->update(['name' => $request->name]);
        return response()->json(['status' => true, 'message' => "Information updated"]);
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
        $imagesFolder = 'admin_images/';
        if (!file_exists(storage_path('app/private/' . $imagesFolder))) {
            mkdir(storage_path('app/private/' . $imagesFolder), 755, true);
        }
        $avatar = $request->file('avatar');
        if ($avatar) {
            $avatar_name = Auth::user()->name . '_' . time() .  '.webp';
            $saveAvatar = $this->resizeAvatar($avatar, $avatar_name);
            if ($saveAvatar) {
                User::where('id', Auth::id())->update([
                    'avatar' => $avatar_name
                ]);
                if (Auth::user()->avatar != 'avatar_default.jpg') {
                    if (file_exists(storage_path('app/private/admin_images/' . Auth::user()->avatar))) {
                        unlink(storage_path('app/private/admin_images/' . Auth::user()->avatar));
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
        $save = Storage::disk('private')->put('admin_images/' . $fileNameToStore, $resizedContent->__toString());
        if ($save) {
            return true;
        }
        return false;
    }
}
