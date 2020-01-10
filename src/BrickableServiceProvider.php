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
        $this->loadViewsFrom(__DIR__ . '/../ressources/views', 'laravel-brickable');
        $this->publishes([
            __DIR__ . '/../ressources/views' => resource_path('views/vendor/laravel-brickable'),
        ], 'laravel-brickable:views');
        $this->publishes([
            __DIR__ . '/../config/brickables.php' => config_path('brickables.php'),
        ], 'laravel-brickable:config');
        if (! class_exists('CreateBricksTable')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/create_bricks_table.php.stub' => database_path(
                    'migrations/' . date('Y_m_d_His', time()) . '_create_bricks_table.php'
                ),
            ], 'laravel-brickable:migrations');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/brickables.php', 'brickables');
        $this->app->bind('Brickables', Brickables::class);
    }
}
