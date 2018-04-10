<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 08/11/2017
 * Time: 15:01
 */
namespace App\Repositories\Eloquents;
use App\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use DB;

class UserRepository implements UserRepositoryInterface
{
    
    public function all()
    {
        return DB::table('users')->get();
    }

    public function getClientAppsByUserId($user_id)
    {
        return DB::table('users')
            ->leftjoin('user_client_relations', 'users.id', '=', 'user_client_relations.user_id')
            ->leftjoin('oauth_clients', 'oauth_clients.id', '=', 'user_client_relations.client_id')
            ->where('users.id', '=', $user_id)
            ->get();
    }

    public function fill($id)
    {
        return User::find($id);
    }

    public function getUserInfoByEmail($email)
    {
       return User::where('email', $email)
                    ->where('del_flg', 0)->first();
    }

    public function getUserByEmailWithoutDelFlg($email) {
        return User::where('email', $email)->first();
    }

    public function getUserNameByEmail($email) {
        return substr($email, 0, strpos($email,'@'));
    }

    public function revolkeAccessTokenGoogle($token)
    {
        $reflector = new \ReflectionClass($token);
        $classProperty = $reflector->getProperty('accessToken');
        $classProperty->setAccessible(true);
        $accessToken = $classProperty->getValue($token);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://accounts.google.com/o/oauth2/revoke?token=". $accessToken);
        curl_exec($ch);
        curl_close($ch);
    }

    public function checkIpRange($ip_ranges, $ip)
    {
        foreach ($ip_ranges as $range) {
            if (strpos($range, '/') == false) {
                $range .= '/32';
            }
            // $range is in IP/CIDR format eg 127.0.0.1/24
            list($range, $netmask) = explode('/', $range, 2);
            $ip_decimal = ip2long($ip);
            $range_decimal = ip2long($range);
            $wildcard_decimal = pow(2, (32 - $netmask)) - 1;
            $netmask_decimal = ~ $wildcard_decimal;
            if (($ip_decimal & $netmask_decimal) == ($range_decimal & $netmask_decimal)) {
                return true;
            }
        }

        return false;
    }

    public function saveUser($email, $manager_flg = 0) {
        $user = new User();
        $user->email       = $email;
        $user->manager_flg = $manager_flg;
        $user->save();

        return $user;
    }

    public function enableUser($user) {
        $user->del_flg = 0;
        return $user->save();
    }

    public function disableUser($user) {
        $user->del_flg = 1;
        return $user->save();
    }

    public function getUserInfo($id) {
        return User::find($id);
    }

    public function save($user) {
        return $user->save();
    }
}