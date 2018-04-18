<?php

namespace App\Services;

class AuthService {

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
}