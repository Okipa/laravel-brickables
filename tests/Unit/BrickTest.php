<?php

namespace Okipa\LaravelBrickables\Tests\Unit;

use Okipa\LaravelBrickables\Brickables\OneTextColumn;
use Okipa\LaravelBrickables\Tests\BrickableTestCase;
use Okipa\LaravelBrickables\Tests\Models\Page;

class BrickTest extends BrickableTestCase
{
    /** @test */
    public function it_renders_html()
    {
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['content' => 'Text content']);
        $this->assertEquals(view($brick->brickable->getViewPath(), $brick->data), $brick->toHtml());
    }
}
