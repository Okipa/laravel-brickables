<?php

namespace Okipa\LaravelBrickables\Brickables;

use Okipa\LaravelBrickables\Abstracts\Brickable;

class OneTextColumn extends Brickable
{
    /** @inheritDoc */
    protected function setStoreValidationRules(): array
    {
        return ['text' => ['required', 'string']];
    }

    /** @inheritDoc */
    protected function setUpdateValidationRules(): array
    {
        return ['text' => ['required', 'string']];
    }
}
