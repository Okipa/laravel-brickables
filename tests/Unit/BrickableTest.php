<?php

namespace Okipa\LaravelBrickables\Tests\Unit;

use Okipa\LaravelBrickables\Brickables\OneTextColumn;
use Okipa\LaravelBrickables\Tests\BrickableTestCase;
use Okipa\LaravelBrickables\Tests\Models\Page;

class BrickableTest extends BrickableTestCase
{
    /** @test */
    public function it_returns_brickable_label()
    {
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['content' => 'Text content']);
        $this->assertEquals((new OneTextColumn)->getLabel(), $brick->brickable->getLabel());
    }

    /** @test */
    public function it_returns_brickable_view_path()
    {
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['content' => 'Text content']);
        $this->assertEquals((new OneTextColumn)->getViewPath(), $brick->brickable->getViewPath());
    }

    // wrong routes

    // get route
}
