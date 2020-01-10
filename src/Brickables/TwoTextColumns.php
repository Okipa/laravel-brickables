<?php

namespace Okipa\LaravelBrickables\Brickables;

use Okipa\LaravelBrickables\Abstracts\Brickable;

class TwoTextColumns extends Brickable
{
    /**
     * @inheritDoc
     */
    public function setLabel(): string
    {
        return __('Two text columns');
    }

    /**
     * @inheritDoc
     */
    public function setViewPath(): string
    {
        return 'laravel-brickables::brickables.two-text-columns';
    }
}
