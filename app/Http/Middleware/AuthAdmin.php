<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Route;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::guard('admin')->id()) {
            return redirect()->route('admin_login');
        }

        return $next($request);
    }
}
