<?php

namespace Modules\RoleManager\Providers;

use Illuminate\Support\ServiceProvider;

class RoleManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Models/Migrations');
        $this->loadViewsFrom(__DIR__ . '/../views', 'rolemanager');
    }

    public function register()
    {
        //
    }
}
