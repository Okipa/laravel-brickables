<?php

namespace Okipa\LaravelBrickables\Tests\Brickables;

use Okipa\LaravelBrickables\Tests\Controllers\BricksController;

class Brickable extends \Okipa\LaravelBrickables\Abstracts\Brickable
{
    /** @inheritDoc */
    public function setFormViewPath(): string
    {
        return 'laravel-brickables::test-brick';
    }

    /** @inheritDoc */
    protected function setStoreValidationRules(): array
    {
        return [];
    }

    /** @inheritDoc */
    protected function setUpdateValidationRules(): array
    {
        return [];
    }

    /** @inheritDoc */
    protected function setBricksControllerClass(): string
    {
        return BricksController::class;
    }
}
