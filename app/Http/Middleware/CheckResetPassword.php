<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Container\Container as App;

class CheckResetPassword
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
        $user = Auth::user();
        $new_user_expired_hours = Config::get('base.new_user_expired_hours');
        $new_user_expired_datetime = date('Y-m-d H:i:s',  strtotime("-$new_user_expired_hours hours" ));

        if ($user->reset_password_flg != User::RESET_PASSWORD_YES)
        {
            if ($user->updated_at <= $new_user_expired_datetime) {
                Auth::logout();
                return redirect()->route('expired_login');
            }

            return redirect()->route('reset_password');
        }

        return redirect()->route('profile');
    }
}
