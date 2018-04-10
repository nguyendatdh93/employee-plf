<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 08/11/2017
 * Time: 15:13
 */
namespace App\Repositories\Contracts;

interface ATBBaseRepositoryInterface
{
    public function all();
    public function fill($id);
}