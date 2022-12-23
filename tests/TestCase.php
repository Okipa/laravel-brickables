<?php

namespace Tests;

use Okipa\LaravelBrickables\BrickablesServiceProvider;
use Okipa\LaravelBrickables\Facades\Brickables;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [BrickablesServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return ['Brickables' => Brickables::class];
    }

    /** @SuppressWarnings("Missing") */
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
