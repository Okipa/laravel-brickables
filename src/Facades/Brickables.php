<?php

namespace Okipa\LaravelBrickables\Facades;

use Illuminate\Support\Facades\Facade;

class Brickables extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Brickables';
    }
}
