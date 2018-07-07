<?php

namespace Imanghafoori\HeyMan;

use Illuminate\Support\ServiceProvider;

class HeyManServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }

    public function register()
    {
        $this->app->singleton('hey_man', HeyMan::class);
    }
}