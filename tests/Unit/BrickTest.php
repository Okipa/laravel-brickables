<?php

namespace Okipa\LaravelBrickables\Tests\Unit;

use Okipa\LaravelBrickables\Abstracts\Brickable;
use Okipa\LaravelBrickables\Brickables\OneTextColumn;
use Okipa\LaravelBrickables\Facades\Brickables;
use Okipa\LaravelBrickables\Models\Brick;
use Okipa\LaravelBrickables\Tests\BrickableTestCase;
use Okipa\LaravelBrickables\Tests\Models\HasBrickablesModel;
use Okipa\LaravelBrickables\Tests\Models\Page;

class BrickTest extends BrickableTestCase
{
    /** @test */
    public function it_renders_html()
    {
        view()->addNamespace('laravel-brickables', 'tests/views');
        $brickable = new Class extends Brickable {
            public function setBrickViewPath(): string
            {
                return 'laravel-brickables::brick-test';
            }

            protected function setStoreValidationRules(): array
            {
                return [];
            }

            protected function setUpdateValidationRules(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable)]);
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(get_class($brickable), ['custom' => 'dummy']);
        $this->assertEquals(view($brick->brickable->getBrickViewPath(), compact('brick')), $brick->toHtml());
    }

    /** @test */
    public function it_can_delete_bricks_until_the_min_number_of_bricks()
    {
        $model = (new HasBrickablesModel)->create();
        $model->addBricks([[OneTextColumn::class], [OneTextColumn::class], [OneTextColumn::class]]);
        $model->clearBricks(OneTextColumn::class);
        $this->assertCount(1, Brick::all());
        $this->assertEquals(3, Brick::first()->position);
    }
}
