<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserClientRelation extends Model
{
    protected $table      = "user_client_relations";
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_id',
        'client_id',
        'del_flg'
    ];
}
