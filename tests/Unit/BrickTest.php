<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Okipa\LaravelBrickables\Abstracts\Brickable;
use Tests\Models\Page;
use Tests\TestCase;

class BrickTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_render_html(): void
    {
        view()->addNamespace('laravel-brickables', 'tests/views');
        $brickable = new class extends Brickable
        {
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
        $page = Page::factory()->create();
        /** @var \Okipa\LaravelBrickables\Models\Brick $brick */
        $brick = $page->addBrick(get_class($brickable), ['custom' => 'dummy']);
        self::assertEquals(view($brick->brickable->getBrickViewPath(), compact('brick')), $brick->toHtml());
    }
}
