<?php

namespace Okipa\LaravelBrickables\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Okipa\LaravelBrickables\Abstracts\Brickable;
use Okipa\LaravelBrickables\Contracts\HasBrickables;
use Okipa\LaravelBrickables\Facades\Brickables;
use Okipa\LaravelBrickables\Models\Brick;
use Okipa\LaravelBrickables\Tests\BrickableTestCase;
use Okipa\LaravelBrickables\Tests\Models\BrickModel;
use Okipa\LaravelBrickables\Tests\Models\HasBrickablesModel;
use Okipa\LaravelBrickables\Tests\Models\Page;
use Okipa\LaravelBrickables\Traits\HasBrickablesTrait;

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
        $registeredPageBrickables = Brickables::getAll();
        $this->assertCount(count(config('brickables.registered')), $registeredPageBrickables);
    }

    /** @test */
    public function it_can_return_brickables_that_can_be_added_to_model()
    {
        $model = (new HasBrickablesModel)->create();
        $brickables = Brickables::getAdditionableTo($model);
        $this->assertCount(count($model->brickables['canOnlyHandle']), $brickables);
        $this->assertInstanceOf($model->brickables['canOnlyHandle'][0], $brickables->first());
        $page = factory(Page::class)->create();
        $pageBrickables = Brickables::getAdditionableTo($page);
        $this->assertCount(count(config('brickables.registered')), $pageBrickables);
    }

    /** @test */
    public function it_can_display_model_bricks_html()
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
            view('laravel-brickables::bricks', ['model' => $page, 'brickableClass' => get_class($brickable)])->render(),
            Brickables::displayBricks($page, get_class($brickable))->toHtml()
        );
    }

    /** @test */
    public function it_can_display_model_bricks_admin_panel_html()
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
            view('laravel-brickables::admin.panel.layout', ['model' => $page])->render(),
            Brickables::displayAdminPanel($page)->toHtml()
        );
    }

    /** @test */
    public function it_can_cast_bricks_to_their_brickable_related_brick_model()
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
        $page->addBricks([[get_class($brickableOne)], [get_class($brickableTwo)]]);
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
    public function it_can_cast_bricks_and_return_them_in_correct_order()
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
        $brickOne = $page->addBrick(get_class($brickableOne));
        $brickTwo = $page->addBrick(get_class($brickableTwo));
        Brick::swapOrder($brickOne, $brickTwo);
        $bricks = Brickables::castBricks(Brick::all());

        $this->assertEquals([1,2], $bricks->pluck('position')->toArray());
    }

    /** @test */
    public function it_can_cast_brick_to_its_brickable_related_brick_model()
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
    public function it_can_get_model_from_create_request()
    {
        $page = factory(Page::class)->create();
        $request = (new Request)->merge(['model_type' => $page->getMorphClass(), 'model_id' => $page->id]);
        $model = Brickables::getModelFromRequest($request);
        $this->assertTrue($page->is($model));
    }

    /** @test */
    public function it_can_get_model_from_edit_request()
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
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(get_class($brickable), []);
        $request = (new Request)->merge(['brick' => $brick]);
        $model = Brickables::getModelFromRequest($request);
        $this->assertTrue($page->is($model));
    }
}
