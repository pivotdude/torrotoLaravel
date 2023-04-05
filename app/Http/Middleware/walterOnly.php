<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class walterOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->role_id != 3) {
            return response()->json(["error" => ["code" => 403, "message" => 'Forbidden for you']], 403);
        }
        return $next($request);
    }
}
