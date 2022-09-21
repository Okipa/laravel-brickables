<?php

namespace Tests\Brickables;

use Tests\Controllers\BricksController;

class Brickable extends \Okipa\LaravelBrickables\Abstracts\Brickable
{
    public function setFormViewPath(): string
    {
        return 'laravel-brickables::brick-test';
    }

    public function validateStoreInputs(): array
    {
        return request()->validate([
            'text_left' => ['required', 'string'],
            'text_right' => ['required', 'string'],
        ]);
    }

    public function validateUpdateInputs(): array
    {
        return request()->validate([
            'text_left' => ['required', 'string'],
            'text_right' => ['required', 'string'],
        ]);
    }

    protected function setBricksControllerClass(): string
    {
        return BricksController::class;
    }
}
