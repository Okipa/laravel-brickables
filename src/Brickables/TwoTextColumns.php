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
            'left_text' => ['required', 'string'],
            'right_text' => ['required', 'string'],
        ];
    }

    /**
     * @inheritDoc
     */
    protected function setUpdateValidationRules(): array
    {
        return [
            'left_text' => ['required', 'string'],
            'right_text' => ['required', 'string'],
        ];
    }
}
