<?php

namespace Okipa\LaravelBrickables;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class BrickablesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     */
    public function boot(Filesystem $filesystem)
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
        $this->publishes([
            __DIR__
            . '/../database/migrations/create_bricks_table.php.stub' => $this->getMigrationFileName($filesystem),
        ], 'laravel-brickables:migrations');
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @param Filesystem $filesystem
     *
     * @return string
     */
    protected function getMigrationFileName(Filesystem $filesystem): string
    {
        $timestamp = date('Y_m_d_His');

        return Collection::make($this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem) {
                return $filesystem->glob($path . '*_create_bricks_tables.php');
            })
            ->push($this->app->databasePath("/migrations/{$timestamp}_create_bricks_tables.php"))
            ->first();
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
