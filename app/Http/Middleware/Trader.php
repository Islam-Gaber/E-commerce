<?php

namespace App\Http\Middleware;

use App\Http\Controllers\api\apiResponseTrait;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;

class Trader
{
    use apiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user() &&  (Auth::user()->isTrader() || Auth::user()->isAdmin())) {
            return $next($request);
        }
        return $this->apiResponse('you have no permision', [], [], [], 422);
    }
}
