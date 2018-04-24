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
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Models\User';
    }

    public function getClientAppsByUserId($user_id)
    {
        return DB::table('users')
            ->leftjoin('user_client_relations', 'users.id', '=', 'user_client_relations.user_id')
            ->leftjoin('oauth_clients', 'oauth_clients.id', '=', 'user_client_relations.client_id')
            ->where('users.id', '=', $user_id)
            ->where('users.del_flg', '=', 0)
            ->where('oauth_clients.del_flg', '=', 0)
            ->where('user_client_relations.del_flg', '=', 0)
            ->get();
    }

    public function findAllByEmail($email)
    {
        return DB::table('users')
            ->where('users.email', '=', $email)
            ->first();
    }

    public function enableUser($id)
    {
        return DB::table('users')
            ->where('users.id', '=', $id)
            ->update(['del_flg' => 0]);
    }
}