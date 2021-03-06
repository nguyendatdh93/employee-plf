<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

//        Passport::enableImplicitGrant();
        //
        \Route::get('oauth/authorize', [
            'uses' => 'App\Http\Controllers\Passport\AuthorizationController@authorize',
        ])->middleware(['web', 'auth']);

        \Route::post('oauth/token', [
            'uses' => 'App\Http\Controllers\Passport\AccessTokenController@issueToken',
            'middleware' => 'throttle',
        ]);

        \Route::get('password/reset/{token}/{email}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    }
}
