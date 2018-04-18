<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;;

class Log extends Model
{
    protected $table      = "logs";
    protected $primaryKey = 'id';
    protected $fillable   = [
        'user_client_id',
        'ip'
    ];
}
