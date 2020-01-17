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

    /**
     * @inheritDoc
     */
    protected function setValidationRules(): array
    {
        return [];
    }

    /** @inheritDoc */
    protected function setBricksControllerClass(): string
    {
        return BricksController::class;
    }
}
