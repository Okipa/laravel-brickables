<?php

namespace Okipa\LaravelBrickable\Facades;

use Illuminate\Support\Facades\Facade;

class Brickables extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor()
    {
        return 'Brickables';
    }
}
