<?php

namespace Okipa\LaravelBrickables\Tests;

use Okipa\LaravelBrickables\BrickablesServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class BrickableTestCase extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [BrickablesServiceProvider::class];
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->withFactories(__DIR__ . '/database/factories');
        include_once __DIR__ . '/../database/migrations/create_bricks_table.php.stub';
        (new \CreateBricksTable)->up();
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }
}
