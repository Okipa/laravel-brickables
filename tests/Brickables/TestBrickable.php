<?php

namespace Okipa\LaravelBrickables\Tests\Brickables;

use Okipa\LaravelBrickables\Abstracts\Brickable;

class TestBrickable extends Brickable
{
    /**
     * @inheritDoc
     */
    public function setLabel(): string
    {
        return 'test-label';
    }

    /**
     * @inheritDoc
     */
    public function setViewPath(): string
    {
        return 'test-view-path';
    }

    /**
     * @inheritDoc
     */
    public function setRoutes(): array
    {
        return [
            'edit' => 'test-edit-route',
            'destroy' => 'test-destroy-route',
        ];
    }
}
