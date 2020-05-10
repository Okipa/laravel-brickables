<?php

namespace Okipa\LaravelBrickables\Brickables;

use Okipa\LaravelBrickables\Abstracts\Brickable;

class OneTextColumn extends Brickable
{
    protected function setStoreValidationRules(): array
    {
        return ['text' => ['required', 'string']];
    }

    protected function setUpdateValidationRules(): array
    {
        return ['text' => ['required', 'string']];
    }
}
