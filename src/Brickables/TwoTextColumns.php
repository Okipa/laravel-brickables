<?php

namespace Okipa\LaravelBrickable\Brickables;

use Okipa\LaravelBrickable\Abstracts\BrickableAbstract;

class TwoTextColumns extends BrickableAbstract
{
    /**
     * @inheritDoc
     */
    protected function setViewPath(): string
    {
        return 'laravel-brickable::two-text-columns';
    }
}
