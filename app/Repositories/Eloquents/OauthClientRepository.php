<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 08/11/2017
 * Time: 15:01
 */
namespace App\Repositories\Eloquents;

use App\Repositories\Contracts\OauthClientRepositoryInterface;
use DB;
use Illuminate\Container\Container as App;

class OauthClientRepository extends ATBBaseRepository implements OauthClientRepositoryInterface
{

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Models\OauthClient';
    }
}