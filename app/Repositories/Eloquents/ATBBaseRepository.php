<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 08/11/2017
 * Time: 16:38
 */

namespace App\Repositories\Eloquents;

use App\Models\UploadFile;
use App\Repositories\Contracts\ATBBaseRepositoryInterface;
use Illuminate\Support\Facades\URL;

class ATBBaseRepository implements ATBBaseRepositoryInterface
{
    public function all()
    {
    }

    public function fill($id)
    {
    }
}