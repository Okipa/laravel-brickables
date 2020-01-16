<?php

namespace Okipa\LaravelBrickables\Brickables;

use Okipa\LaravelBrickables\Abstracts\Brickable;

class TwoTextColumns extends Brickable
{
    /**
     * @inheritDoc
     */
    protected function setValidationRules(): array
    {
        return [
            'left_text' => ['required', 'string'],
            'right_text' => ['required', 'string'],
        ];
    }
}
