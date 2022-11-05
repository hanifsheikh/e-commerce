<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $required_permissions)
    {
        $userPermissionArray = [];
        if ($request->user()->role) {
            foreach ($request->user()->role->permissions as $permission) {
                array_push($userPermissionArray, $permission->permission_name);
            }
            $required_permissions = explode('|', $required_permissions);
            if (array_intersect($required_permissions, $userPermissionArray)) {
                return $next($request);
            }
        }
        $request->user()->currentAccessToken()->delete();
        return abort(401);
    }
}
