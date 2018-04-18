<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\AuthService;
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
        $auth_service    = new AuthService();
        $change_password = $auth_service->checkResetPassword();
        if ($change_password == AuthService::NO_CHANGE_PASSWORD) {
            return redirect()->route('reset_password');
        } elseif ($change_password == AuthService::EXPIRED_PASSWORD) {
            return redirect()->route('expired_login');
        } else {
            return redirect()->route('profile');
        }
    }
}
