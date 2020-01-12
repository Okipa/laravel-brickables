<?php

namespace Okipa\LaravelBrickables\Brickables;

use Okipa\LaravelBrickables\Abstracts\Brickable;

class OneTextColumn extends Brickable
{
    /**
     * @inheritDoc
     */
    public function setValidationRules(): array
    {
        return [
            'content' => ['required' => 'string'],
        ];
    }
}
