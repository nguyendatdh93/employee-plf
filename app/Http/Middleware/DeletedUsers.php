<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class DeletedUsers
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
        if (empty($user)) {
            return $next($request);
        }


        if ($user->del_flg) {
            Auth::logout();

            return redirect('/login')->withErrors(['email' => 'Your account is removed!'])->withInput(['email' => $user->email]);
        }

        return $next($request);
    }
}
