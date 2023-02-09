<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;

class UserMiddleware
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
        $path = $request->path();
        if (($path == 'signup' || $path == '/') && session('userEmail')) {
            return redirect()->route('user.dashboard');
        }

        if (($path != 'signup' && $path != '/') && !session('userEmail')) {
            return redirect()->route('userLogin');
        }
        return $next($request);
    }
}
