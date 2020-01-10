<?php

namespace Okipa\LaravelBrickables;

use Illuminate\Support\ServiceProvider;

class BrickablesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../ressources/views', 'laravel-brickables');
        $this->loadTranslationsFrom(__DIR__ . '/../ressources/lang', 'laravel-brickables');
        $this->publishes([
            __DIR__ . '/../config/brickables.php' => config_path('brickables.php'),
        ], 'laravel-brickables:config');
        $this->publishes([
            __DIR__ . '/../ressources/views' => resource_path('views/vendor/laravel-brickables'),
        ], 'laravel-brickables:views');
        $this->publishes([
            __DIR__ . '/../ressources/lang' => resource_path('lang/vendor/laravel-brickables'),
        ]);
        if (! class_exists('CreateBricksTable')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/create_bricks_table.php.stub' => database_path(
                    'migrations/' . date('Y_m_d_His', time()) . '_create_bricks_table.php'
                ),
            ], 'laravel-brickables:migrations');
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
