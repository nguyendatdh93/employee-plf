<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class OauthClient extends Model implements Authenticatable
{
    const CLIENT_NAME_MAX_LIMIT  = 255;
    const URL_REDIRECT_MAX_LIMIT = 255;

    const ERROR_IP           = 'error_ip';
    const ERROR_PERMISSION   = 'error_permission';
    const ERROR_UNAUTHORIZED = 'error_unauthorized';

    const HTTP_CODE_UNAUTHORIZED = 401;
    const HTTP_CODE_FORBIDDEN    = 403;

    use AuthenticableTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','name', 'secret', 'redirect', 'ip_secure', 'personal_access_client', 'password_client', 'revoked'
    ];
}
