<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 08/11/2017
 * Time: 15:13
 */
namespace App\Repositories\Contracts;

interface UserRepositoryInterface extends ATBBaseRepositoryInterface
{
    public function getClientAppsByUserId($user_id);

    public function findAllByEmail($email);
}