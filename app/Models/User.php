<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\OverrideResetPassword;

class User extends Authenticatable
{
    const NAME_MAX_LIMIT  = 100;
    const EMAIL_MAX_LIMIT = 200;

    const RESET_PASSWORD_NO     = 0;
    const RESET_PASSWORD_YES    = 1;
    const RESET_PASSWORD_EXTEND = 2;

    const ACTIVE     = 1;
    const DELETE_FLG = 1;

    use Notifiable;
    use HasApiTokens, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new OverrideResetPassword($token));
    }
}
