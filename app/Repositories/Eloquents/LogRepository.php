<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 08/11/2017
 * Time: 15:01
 */
namespace App\Repositories\Eloquents;

use App\Repositories\Contracts\LogRepositoryInterface;
use DB;
use Illuminate\Container\Container as App;

class LogRepository extends ATBBaseRepository implements LogRepositoryInterface
{

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Models\Log';
    }
}