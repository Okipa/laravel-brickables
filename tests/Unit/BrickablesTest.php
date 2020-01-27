<?php

namespace Okipa\LaravelBrickables\Tests\Unit;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Okipa\LaravelBrickables\Abstracts\Brickable;
use Okipa\LaravelBrickables\Facades\Brickables;
use Okipa\LaravelBrickables\Models\Brick;
use Okipa\LaravelBrickables\Tests\BrickableTestCase;
use Okipa\LaravelBrickables\Tests\Models\BrickModel;
use Okipa\LaravelBrickables\Tests\Models\HasBrickablesModel;
use Okipa\LaravelBrickables\Tests\Models\Page;

class BrickablesTest extends BrickableTestCase
{
    /** @test */
    public function it_can_return_all_registered_brickables()
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
        $registeredPageBrickables = Brickables::getAll(Page::class);
        $registeredBrickables = Brickables::getAll(Page::class);
        $this->assertCount(count(config('brickables.registered')), $registeredPageBrickables);
        $this->assertCount(count(config('brickables.registered')), $registeredBrickables);
        $this->assertInstanceOf(Collection::class, $registeredPageBrickables);
        $this->assertInstanceOf(Collection::class, $registeredBrickables);
    }

    /** @test */
    public function it_can_return_all_model_handlable_brickables()
    {
        $brickables = Brickables::getAll(HasBrickablesModel::class);
        $model = (new HasBrickablesModel);
        $this->assertCount(count($model->brickables['canOnlyHandle']), $brickables);
        $this->assertInstanceOf($model->brickables['canOnlyHandle'][0], $brickables->first());
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
            view('laravel-brickables::admin.panel.layout', ['model' => $page])->toHtml(),
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

    /** @test */
    public function it_can_get_model_from_current_url()
    {
        $page = factory(Page::class)->create();
        $request = (new Request)->merge(['model_type' => $page->getMorphClass(), 'model_id' => $page->id]);
        $model = Brickables::getModelFromRequest($request);
        $this->assertTrue($page->is($model));
    }
}
