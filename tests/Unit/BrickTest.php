<?php

namespace Okipa\LaravelBrickable\Tests\Unit;

use Okipa\LaravelBrickable\Tests\BrickableTestCase;
use Okipa\LaravelBrickable\Tests\Models\Page;

class BrickTest extends BrickableTestCase
{
    /** @test */
    public function it_returns_brick_type_label()
    {
        $page = factory(Page::class)->create();
        $brick = $page->addBrick('oneTextColumn', ['content' => 'Text content']);
        $this->assertEquals(config('brickable.types.oneTextColumn.label'), $brick->getLabel());
    }

    /** @test */
    public function it_returns_brick_type_view_path()
    {
        $page = factory(Page::class)->create();
        $brick = $page->addBrick('oneTextColumn', ['content' => 'Text content']);
        $this->assertEquals(config('brickable.types.oneTextColumn.view'), $brick->getViewPath());
    }
}
