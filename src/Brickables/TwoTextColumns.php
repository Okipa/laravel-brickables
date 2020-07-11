<?php

namespace Okipa\LaravelBrickables\Brickables;

use Okipa\LaravelBrickables\Abstracts\Brickable;

class TwoTextColumns extends Brickable
{
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
}
