<?php

namespace App\Repositories\Eloquents;

use App\Repositories\Contracts\UserClientRelationRepositoryInterface;

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
}