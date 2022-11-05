<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Seller
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
            if ($request->user()->documents_approved_at) {
                return $next($request);
            }
        }
        $request->user()->currentAccessToken()->delete();
        return abort(401);
    }
}
