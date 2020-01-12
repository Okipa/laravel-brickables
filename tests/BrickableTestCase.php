<?php

namespace Okipa\LaravelBrickables\Tests;

use Okipa\LaravelBrickables\BrickablesServiceProvider;
use Okipa\LaravelBrickables\Facades\Brickables;
use Orchestra\Testbench\TestCase;

abstract class BrickableTestCase extends TestCase
{
    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    protected function getPackageProviders($app)
    {
        return [BrickablesServiceProvider::class];
    }

    /**
     * @inheritDoc
     */
    protected function getPackageAliases($app)
    {
        return [
            'Brickables' => Brickables::class,
        ];
    }

    /**
     * @inheritDoc
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
