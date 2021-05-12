<?php

namespace Okipa\LaravelBrickables\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Okipa\LaravelBrickables\Abstracts\Brickable;
use Okipa\LaravelBrickables\Brickables\OneTextColumn;
use Okipa\LaravelBrickables\Models\Brick;
use Okipa\LaravelBrickables\Tests\BrickableTestCase;
use Okipa\LaravelBrickables\Tests\Models\HasOneConstrainedBrickableModel;
use Okipa\LaravelBrickables\Tests\Models\Page;

class BrickTest extends BrickableTestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_render_html(): void
    {
        view()->addNamespace('laravel-brickables', 'tests/views');
        $brickable = new Class extends Brickable {
            public function setBrickViewPath(): string
            {
                return 'laravel-brickables::brick-test';
            }

            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable)]);
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(get_class($brickable), ['custom' => 'dummy']);
        self::assertEquals(view($brick->brickable->getBrickViewPath(), compact('brick')), $brick->toHtml());
    }
}
