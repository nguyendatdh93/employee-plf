<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Auth;

class CheckAuth
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
        if (Auth::user()->active != User::ACTIVE || Auth::user()->del_flg == User::DELETE_FLG) {
            Auth::logout();

            return redirect('/login')->with('error', __('login_user.error_can_not_login'));
        }

        return $next($request);
    }
}
