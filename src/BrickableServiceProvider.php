<?php

namespace Okipa\LaravelBrickable;

use Illuminate\Support\ServiceProvider;

class BrickableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/brickable.php' => config_path('brickable.php'),
            ], 'laravel-brickable:config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/brickable.php', 'brickable');
    }
}
