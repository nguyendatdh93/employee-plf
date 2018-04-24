<?php

namespace App\Repositories\Contracts;

interface UserClientRelationRepositoryInterface extends ATBBaseRepositoryInterface
{
    public function removeByUserId($user_id);
}