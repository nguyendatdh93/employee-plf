<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 08/11/2017
 * Time: 15:01
 */
namespace App\Repositories\Eloquents;

use App\Repositories\Contracts\UserRepositoryInterface;
use DB;
use Illuminate\Container\Container as App;

class UserRepository extends ATBBaseRepository implements UserRepositoryInterface
{
    public function __construct()
    {

    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'App\User';
    }

    public function getClientAppsByUserId($user_id)
    {
        return DB::table('users')
            ->leftjoin('user_client_relations', 'users.id', '=', 'user_client_relations.user_id')
            ->leftjoin('oauth_clients', 'oauth_clients.id', '=', 'user_client_relations.client_id')
            ->where('users.id', '=', $user_id)
            ->where('del_flg', '=', 0)
            ->get();
    }

    public function removeUser($user_id)
    {
        return DB::table('users')->where('id', '=', $user_id)->update(['del_flg' => 1]);
    }

    public function checkResetPasswordFlg($user_id)
    {
        return DB::table('users')->where('id', '=', $user_id)->select('reset_password_flg')->get();
    }
}