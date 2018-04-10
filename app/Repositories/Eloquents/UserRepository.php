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
        return DB::table('users')->where('del_flg', '=', 0)->get();
    }

    public function fill($id)
    {
        return User::find($id);
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
        return DB::table('users')->where('id', '=', $user_id)->update(['del_flg' => 1]);;
    }
}