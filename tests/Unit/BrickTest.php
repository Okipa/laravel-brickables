<?php

namespace Okipa\LaravelBrickables\Tests\Unit;

use Okipa\LaravelBrickables\Abstracts\Brickable;
use Okipa\LaravelBrickables\Tests\BrickableTestCase;
use Okipa\LaravelBrickables\Tests\Models\Page;

class BrickTest extends BrickableTestCase
{
    /** @test */
    public function it_renders_html()
    {
        view()->addNamespace('laravel-brickables', 'tests/dummy/views');
        $brickable = new Class extends Brickable {
            public function setBrickViewPath(): string
            {
                return 'laravel-brickables::dummy';
            }

            public function setValidationRules(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable)]);
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(get_class($brickable), ['data' => 'dummy']);
        $this->assertEquals(view($brick->brickable->getBrickViewPath(), $brick->data), $brick->toHtml());
    }
}
