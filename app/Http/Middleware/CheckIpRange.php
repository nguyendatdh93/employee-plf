<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Closure;
use Config;
use Response;

class CheckIpRange
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
        $auth_service = new AuthService();
        if (!$auth_service->checkIpRange(Config::get('base.ip_range'), $request->ip())) {
            return redirect()->route('404');
        }

        return $next($request);
    }
}
