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
    public function getUserInfoByEmail($email);
    public function getUserByEmailWithoutDelFlg($email);
    public function getUserNameByEmail($email);
    public function saveUser($email);
    public function disableUser($user_id);
    public function enableUser($user_id);
    public function getUserInfo($id);
    public function save($user);
}