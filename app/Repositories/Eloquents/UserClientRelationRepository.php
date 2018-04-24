<?php

namespace App\Repositories\Eloquents;

use App\Repositories\Contracts\UserClientRelationRepositoryInterface;
use DB;

class UserClientRelationRepository extends ATBBaseRepository implements UserClientRelationRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Models\UserClientRelation';
    }

    public function removeByUserId($user_id)
    {
        if ($user_id) {
            return DB::table('user_client_relations')
                ->where('user_client_relations.user_id', '=', $user_id)
                ->delete();
        }
    }
}