<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SellerInactive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() instanceof \App\Models\Seller) {
            return $next($request);
        }
        $request->user()->currentAccessToken()->delete();
        return abort(401);
    }
}
