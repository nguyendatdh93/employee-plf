<?php

namespace App\Http\Middleware;

use App\Repositories\Eloquents\UserRepository;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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
        $userRepository = new UserRepository(new App());
        $reset_password_flg = $userRepository->checkResetPasswordFlg(Auth::id());
        if ($reset_password_flg[0]->reset_password_flg != User::RESETTED_PASSWORD_FLG)
        {
            return redirect()->route('reset_password');
        }

        return redirect()->route('change_password');
    }
}
