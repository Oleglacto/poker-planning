<?php

namespace App\Providers;

use App\Services\DbRoomManagementService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('App\Contracts\RoomManagementInterface', function ($app) {
            return new DbRoomManagementService();
        });
    }
}
