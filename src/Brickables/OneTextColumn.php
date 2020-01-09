<?php

namespace Okipa\LaravelBrickable\Brickables;

use Okipa\LaravelBrickable\Abstracts\BrickableAbstract;

class OneTextColumn extends BrickableAbstract
{
    /**
     * @inheritDoc
     */
    protected function setViewPath(): string
    {
        return 'laravel-brickable::one-text-column';
    }
}
