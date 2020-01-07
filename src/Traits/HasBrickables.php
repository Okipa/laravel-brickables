<?php

namespace Okipa\LaravelBrickable\Traits;

trait HasBrickables
{
    public function bricks()
    {
        return $this->morphToMany(config('brickables.brick_model'), 'brickable');
    }

    public function addBrick()
    {

    }
}
