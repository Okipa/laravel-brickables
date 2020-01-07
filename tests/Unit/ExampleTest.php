<?php

namespace Okipa\LaravelBrickable\Tests\Unit;

use Okipa\LaravelBrickable\Tests\BrickableTestCase;

class ExampleTest extends BrickableTestCase
{
    /** @test */
    public function true_is_true()
    {
        $user = factory(User::class)->make();
        $this->assertTrue(true);
    }
}
