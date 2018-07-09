<?php

namespace Imanghafoori\HeyMan;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class HeyManServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');

        Route::matched(function (RouteMatched $eventObj) {
            $currentUrl = $eventObj->route->uri;
            $urls = $this->app['hey_man']->getUrls();

            if (isset($urls[$currentUrl]['role'])) {
                if (! auth()->user()->hasRole($urls[$currentUrl]['role'])) {
                    throw new AuthorizationException();
                }
            }

            $routeNames = $this->app['hey_man']->getRouteNames();
            $currentRouteName = $eventObj->route->getName();

            if (isset($routeNames[$currentRouteName]['role'])) {
                if (! auth()->user()->hasRole($routeNames[$currentRouteName]['role'])) {
                    throw new AuthorizationException();
                }
            }

            $actions = $this->app['hey_man']->getActions();
            $currentActionName = $eventObj->route->getActionName();

            if (isset($actions[$currentActionName]['role'])) {
                if (! auth()->user()->hasRole($actions[$currentActionName]['role'])) {
                    throw new AuthorizationException();
                }
            }

        });
    }

    public function register()
    {
        $this->app->singleton('hey_man', HeyMan::class);

        $this->mergeConfigFrom(
            __DIR__.'/../config/heyMan.php',
            'heyMan'
        );
    }
}