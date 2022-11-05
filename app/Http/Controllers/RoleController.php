<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('HasPermission:role-view')->only(['fetchPermissions', 'fetchRoles']);
        $this->middleware('HasPermission:role-create')->only('store');
        $this->middleware('HasPermission:role-update')->only('update');
        $this->middleware('HasPermission:role-delete')->only('destroy');
    }
    public function fetchPermissions()
    {
        $permissions = Permission::all()->groupBy('permission_group');
        return response()->json($permissions);
    }
    public function fetchRoles()
    {
        $roles = Role::with('permissions')->get();
        return response()->json($roles);
    }
    public function store(Request $request)
    {

        $role = Role::where('role_name', '=', $request->role_name)->get();

        if (!count($role)) {
            $role = Role::create(['role_name' => $request->role_name]);
            foreach ($request->permissions as $permission) {
                RolePermission::create([
                    "role_id" => $role->id,
                    "permission_id" => $permission
                ]);
            }
            return response()->json(["message" => "Role <b>" .  $role->role_name . "</b> has been created!", "status" => true]);
        }
        return response()->json(["message" => "Role <b>" . $request->role_name . "</b> already exist.", "status" => false]);
    }
    public function update(Request $request)
    {

        $role = Role::where('role_name', '=', $request->role_name)->get();

        if (!count($role) || $role[0]->id == $request->role_id) {
            $role = Role::find($request->role_id)->update(['role_name' => $request->role_name]);
            RolePermission::where('role_id', $request->role_id)->delete();
            foreach ($request->permissions as $permission) {
                RolePermission::create([
                    "role_id" => $request->role_id,
                    "permission_id" => $permission
                ]);
            }
            return response()->json(["message" => "Role <b>" . $request->role_name . "</b> has been updated!", "status" => true]);
        }
        return response()->json(["message" => "Role <b>" . $request->role_name . "</b> already exist!", "status" => false]);
    }
    public function destroy(Request $request)
    {
        $role = Role::find($request->id);
        if ($role) {
            RolePermission::where('role_id', $role->id)->delete();
            $role_name = $role->role_name;
            $role->delete();
            return response()->json(['message' => "Role <b>" . $role_name . "</b> has been deleted.", "status" => true]);
        }
        return response()->json(['message' =>  "Already deleted.", "status" => false]);
    }
}
