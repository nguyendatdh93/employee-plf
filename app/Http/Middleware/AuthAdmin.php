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

        if (Route::currentRouteName() == 'admin_login') {
            return redirect()->route('user_managerment');
        }

        return $next($request);
    }
}
