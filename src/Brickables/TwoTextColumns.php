<?php

namespace Okipa\LaravelBrickables\Brickables;

use Okipa\LaravelBrickables\Abstracts\Brickable;

class TwoTextColumns extends Brickable
{
    /**
     * @inheritDoc
     */
    protected function setStoreValidationRules(): array
    {
        return [
            'text_left' => ['required', 'string'],
            'text_right' => ['required', 'string'],
        ];
    }

    /**
     * @inheritDoc
     */
    protected function setUpdateValidationRules(): array
    {
        return [
            'text_left' => ['required', 'string'],
            'text_right' => ['required', 'string'],
        ];
    }
}
