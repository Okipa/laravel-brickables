<?php

namespace Okipa\LaravelBrickable\Tests\Unit;

use Okipa\LaravelBrickable\Facades\Brickables;
use Okipa\LaravelBrickable\Tests\BrickableTestCase;

class BrickablesTest extends BrickableTestCase
{
    /** @test */
    public function it_returns_available_brickable_types()
    {
        $this->assertEquals(config('brickable.types'), Brickables::getTypes());
    }

    /** @test */
    public function it_returns_brickable_type()
    {
        $this->assertEquals(config('brickable.types.oneTextColumn'), Brickables::getType('oneTextColumn'));
    }
}
