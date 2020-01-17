<?php

namespace Okipa\LaravelBrickables\Tests\Unit;

use Illuminate\Support\Collection;
use Okipa\LaravelBrickables\Abstracts\Brickable;
use Okipa\LaravelBrickables\Facades\Brickables;
use Okipa\LaravelBrickables\Models\Brick;
use Okipa\LaravelBrickables\Tests\BrickableTestCase;
use Okipa\LaravelBrickables\Tests\Models\BrickModel;
use Okipa\LaravelBrickables\Tests\Models\Page;

class BrickablesTest extends BrickableTestCase
{
    /** @test */
    public function it_returns_all_registered_brickables()
    {
        $brickable = new Class extends Brickable {
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
        $brickables = Brickables::getAll();
        $this->assertCount(count(config('brickables.registered')), $brickables);
        $this->assertInstanceOf(Collection::class, $brickables);
    }

    /** @test */
    public function it_displays_model_bricks_html()
    {
        view()->addNamespace('laravel-brickables', 'tests/views');
        $brickable = new Class extends Brickable {
            public function setBrickViewPath(): string
            {
                return 'laravel-brickables::test-brick';
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
        $page->addBrick(get_class($brickable), ['custom' => 'dummy']);
        $this->assertEquals(
            view('laravel-brickables::bricks', ['model' => $page])->toHtml(),
            Brickables::bricks($page)->toHtml()
        );
    }

    /** @test */
    public function it_displays_model_bricks_admin_panel_html()
    {
        view()->addNamespace('laravel-brickables', 'tests/views');
        $brickable = new Class extends Brickable {
            public function setBrickViewPath(): string
            {
                return 'laravel-brickables::test-brick';
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
        Brickables::routes();
        $page = factory(Page::class)->create();
        $page->addBrick(get_class($brickable), ['custom' => 'dummy']);
        $this->assertEquals(
            view('laravel-brickables::admin.panel', ['model' => $page])->toHtml(),
            Brickables::adminPanel($page)->toHtml()
        );
    }

    /** @test */
    public function it_cast_bricks_to_their_brickable_related_brick_model()
    {
        $brickableOne = new Class extends Brickable {
            protected function setStoreValidationRules(): array
            {
                return [];
            }

            protected function setUpdateValidationRules(): array
            {
                return [];
            }
        };
        $brickableTwo = new Class extends Brickable {
            protected function setBrickModelClass(): string
            {
                return BrickModel::class;
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
        config()->set('brickables.registered', [get_class($brickableOne), get_class($brickableTwo)]);
        $page = factory(Page::class)->create();
        $page->addBricks([[get_class($brickableOne), []], [get_class($brickableTwo), []]]);
        $bricks = Brick::all();
        $this->assertEquals(
            Brick::class,
            $bricks->where('brickable_type', get_class($brickableOne))->first()->getMorphClass()
        );
        $this->assertEquals(
            Brick::class,
            $bricks->where('brickable_type', get_class($brickableTwo))->first()->getMorphClass()
        );
        $bricks = Brickables::castBricks($bricks);
        $this->assertCount(2, $bricks);
        $this->assertInstanceOf(Brick::class, $bricks->where('brickable_type', get_class($brickableOne))->first());
        $this->assertInstanceOf(BrickModel::class, $bricks->where('brickable_type', get_class($brickableTwo))->first());
    }

    /** @test */
    public function it_cast_brick_to_its_brickable_related_brick_model()
    {
        $brickable = new Class extends Brickable {
            protected function setBrickModelClass(): string
            {
                return BrickModel::class;
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
        $page->addBricks([[get_class($brickable), []]]);
        $brick = Brick::first();
        $this->assertEquals(
            Brick::class,
            $brick->where('brickable_type', get_class($brickable))->first()->getMorphClass()
        );
        $brick = Brickables::castBrick($brick);
        $this->assertInstanceOf(BrickModel::class, $brick->where('brickable_type', get_class($brickable))->first());
    }
}
