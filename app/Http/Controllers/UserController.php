<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;
use App\Models\User;
use App\Models\UserRole;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('HasPermission:user-view')->only(['index', 'searchUsers']);
        $this->middleware('HasPermission:user-update')->only('update');
    }
    public function index()
    {
        $users = User::all();
        return new UserCollection($users);
    }
    public function searchUsers(Request $request)
    {
        $users = User::where('name', 'like', '%' . $request->searchString . '%')->orWhere('email', '=', $request->searchString)->get();
        return new UserCollection($users);
    }
    public function update(Request $request)
    {
        $user = User::find($request->user_id);
        $user_role = UserRole::where('user_id', $request->user_id);
        if (!count($user_role->get())) {
            UserRole::create([
                'user_id' => $request->user_id,
                'role_id' => $request->role_id
            ]);
        } else {
            $user_role->update([
                'role_id' => $request->role_id
            ]);
        }
        return response()->json(['message' => "User <b>" . $user->name . "</b>'s role has been updated.", "status" => true]);
    }
    public function destroy(Request $request)
    {
        $user = User::find($request->id);
        if ($user) {
            $user_name = $user->name;
            $user->delete();
            return response()->json(['message' => "User <b>" . $user_name . "</b> has been deleted.", "status" => true]);
        }
        return response()->json(['message' =>  "Already deleted.", "status" => false]);
    }
}
