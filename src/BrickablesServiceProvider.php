<?php

namespace Okipa\LaravelBrickables;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BrickablesServiceProvider extends ServiceProvider
{
    public function boot(Filesystem $filesystem): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-brickables');
        $this->publishes([
            __DIR__ . '/../config/brickables.php' => config_path('brickables.php'),
        ], 'laravel-brickables:config');
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/laravel-brickables'),
        ], 'laravel-brickables:views');
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'laravel-brickables:migrations');
        $this->declareBladeDirectives();
    }

    protected function declareBladeDirectives(): void
    {
        Blade::directive('brickablesCss', static function () {
            return "<?php echo view('laravel-brickables::resources.css')->toHtml(); ?>";
        });
        Blade::directive('brickablesJs', static function () {
            return "<?php echo view('laravel-brickables::resources.js')->toHtml(); ?>";
        });
        Blade::directive('brickableResourcesCompute', static function () {
            return "<?php echo view('laravel-brickables::resources.compute')->toHtml(); ?>";
        });
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/brickables.php', 'brickables');
        $this->app->bind('brickables', Brickables::class);
    }
}
