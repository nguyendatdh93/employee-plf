<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Config;

class AuthService {
    const CHANGED_PASSWORD   = 1;
    const EXPIRED_PASSWORD   = 2;
    const NO_CHANGE_PASSWORD = 3;
    /**
     * @param $ip_secure
     * @param $ip
     * @return bool
     */
    public function checkIpRange($ip_range, $ip)
    {
        foreach ($ip_range as $range) {
            if (strpos($range, '/') == false) {
                $range .= '/32';
            }
            // $range is in IP/CIDR format eg 127.0.0.1/24
            list($range, $netmask) = explode('/', $range, 2);
            $ip_decimal       = ip2long($ip);
            $range_decimal    = ip2long($range);
            $wildcard_decimal = pow(2, (32 - $netmask)) - 1;
            $netmask_decimal  = ~ $wildcard_decimal;
            if (($ip_decimal & $netmask_decimal) == ($range_decimal & $netmask_decimal)) {
                return true;
            }
        }

        return false;
    }

    public function checkResetPassword()
    {
        $user = Auth::user();
        $new_user_expired_hours = Config::get('base.new_user_expired_hours');
        $new_user_expired_datetime = date('Y-m-d H:i:s',  strtotime("-$new_user_expired_hours hours" ));

        if ($user->reset_password_flg != User::RESET_PASSWORD_YES)
        {
            if ($user->updated_at <= $new_user_expired_datetime) {
                Auth::logout();

                return self::EXPIRED_PASSWORD;
            }

            return self::NO_CHANGE_PASSWORD;
        }

        return self::CHANGED_PASSWORD;
    }
}