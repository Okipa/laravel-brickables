<?php

namespace Okipa\LaravelBrickables\Brickables;

use Okipa\LaravelBrickables\Abstracts\Brickable;

class OneTextColumn extends Brickable
{
    public function validateStoreInputs(): array
    {
        return request()->validate(['text' => ['required', 'string']]);
    }

    public function validateUpdateInputs(): array
    {
        return request()->validate(['text' => ['required', 'string']]);
    }
}
