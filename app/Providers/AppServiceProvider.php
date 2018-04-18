<?php

namespace App\Providers;

use App\Repositories\Contracts\LogRepositoryInterface;
use App\Repositories\Contracts\OauthClientRepositoryInterface;
use App\Repositories\Contracts\UserClientRelationRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquents\LogRepository;
use App\Repositories\Eloquents\OauthClientRepository;
use App\Repositories\Eloquents\UserClientRelationRepository;
use App\Repositories\Eloquents\UserRepository;
use App\Repositories\InterfaceRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->singleton(
            UserClientRelationRepositoryInterface::class,
            UserClientRelationRepository::class
        );

        $this->app->singleton(
            OauthClientRepositoryInterface::class,
            OauthClientRepository::class
        );

        $this->app->singleton(
            LogRepositoryInterface::class,
            LogRepository::class
        );
    }
}
