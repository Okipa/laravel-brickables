<?php

namespace Okipa\LaravelBrickables\Brickables;

use Okipa\LaravelBrickables\Abstracts\Brickable;

class TwoTextColumns extends Brickable
{
    /**
     * @inheritDoc
     */
    public function setValidationRules(): array
    {
        return [
            'left_content' => ['required' => 'string'],
            'right_content' => ['required' => 'string'],
        ];
    }
}
