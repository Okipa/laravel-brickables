<?php

namespace Okipa\LaravelBrickables\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelBrickables\Abstracts\Brickable;
use Okipa\LaravelBrickables\Brickables\OneTextColumn;
use Okipa\LaravelBrickables\Brickables\TwoTextColumns;
use Okipa\LaravelBrickables\Contracts\HasBrickables;
use Okipa\LaravelBrickables\Exceptions\BrickableCannotBeHandledException;
use Okipa\LaravelBrickables\Exceptions\InvalidBrickableClassException;
use Okipa\LaravelBrickables\Exceptions\NotRegisteredBrickableClassException;
use Okipa\LaravelBrickables\Facades\Brickables;
use Okipa\LaravelBrickables\Models\Brick;
use Okipa\LaravelBrickables\Tests\BrickableTestCase;
use Okipa\LaravelBrickables\Tests\Models\HasMultipleBrickablesWithConstraintsModel;
use Okipa\LaravelBrickables\Tests\Models\HasOneBrickableWithConstraintsModel;
use Okipa\LaravelBrickables\Tests\Models\Page;
use Okipa\LaravelBrickables\Traits\HasBrickablesTrait;

class HasBrickablesTest extends BrickableTestCase
{
    /** @test */
    public function it_cannot_add_invalid_brickable_class()
    {
        $brickable = new Class {
            //
        };
        $page = factory(Page::class)->create();
        $this->expectException(InvalidBrickableClassException::class);
        $page->addBrick(get_class($brickable), []);
    }

    /** @test */
    public function it_cannot_add_not_registered_brickable_class()
    {
        $brickable = new Class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        $page = factory(Page::class)->create();
        $this->expectException(NotRegisteredBrickableClassException::class);
        $page->addBrick(get_class($brickable), []);
    }

    public function it_cannot_add_not_handlable_brickable()
    {
        $brickable = new Class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        $model = (new HasOneBrickableWithConstraintsModel)->create();
        $model->addBrick(get_class($brickable));
        $this->expectException(BrickableCannotBeHandledException::class);
    }

    public function it_can_check_if_model_can_handle_brickable()
    {
        $model = new Class extends Model implements HasBrickables {
            use HasBrickablesTrait;

            public array $brickables = [
                'canOnlyHandle' => [OneTextColumn::class],
            ];
        };
        $this->assertTrue($model->canHandle(OneTextColumn::class));
        $this->assertFalse($model->canHandle(TwoTextColumns::class));
    }

    /** @test */
    public function it_can_add_brick()
    {
        $brickable = new Class extends Brickable {
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
        $brick = $page->addBrick(get_class($brickable), []);
        $this->assertTrue($brick->is(Brick::first()));
    }

    /** @test */
    public function it_can_add_brick_with_a_custom_brick_model()
    {
        $brickModel = new class extends Brick {
            public function dummy(): string
            {
                return 'dummy';
            }
        };
        $brickable = new Class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        config()->set('brickables.bricks.model', get_class($brickModel));
        config()->set('brickables.registered', [get_class($brickable)]);
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(get_class($brickable), []);
        $this->assertTrue($brick->is($brickModel->first()));
        $this->assertEquals('dummy', $brick->dummy());
    }

    /** @test */
    public function it_can_handle_max_number_of_brick_on_addition_and_auto_remove_olders()
    {
        $model = (new HasMultipleBrickablesWithConstraintsModel)->create();
        $brick1 = $model->addBrick(OneTextColumn::class);
        $brick2 = $model->addBrick(TwoTextColumns::class);
        $brick3 = $model->addBrick(OneTextColumn::class);
        $brick4 = $model->addBrick(TwoTextColumns::class);
        $bricks = Brick::all();
        $this->assertFalse($brick1->is($bricks->get(0)));
        $this->assertFalse($brick2->is($bricks->get(1)));
        $this->assertTrue($brick3->is($bricks->get(0)));
        $this->assertTrue($brick4->is($bricks->get(1)));
    }

    /** @test */
    public function it_can_add_bricks_with_max_number_not_exceeded()
    {
        $brickable = new Class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [
            OneTextColumn::class,
            TwoTextColumns::class,
            get_class($brickable)
        ]);
        $model = (new HasMultipleBrickablesWithConstraintsModel)->create();
        $brick1 = $model->addBrick(OneTextColumn::class);
        $brick2 = $model->addBrick(TwoTextColumns::class);
        $brick3 = $model->addBrick(get_class($brickable));
        $bricks = Brick::all();
        $this->assertTrue($brick1->is($bricks->get(0)));
        $this->assertTrue($brick2->is($bricks->get(1)));
        $this->assertTrue($brick3->is($bricks->get(2)));
    }

    /** @test */
    public function it_can_add_bricks()
    {
        $brickable = new Class extends Brickable {
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
        $bricks = $page->addBricks([[get_class($brickable), []], [get_class($brickable), []]]);
        $this->assertCount(2, $bricks);
        $this->assertEmpty(Brick::all()->diff($bricks));
    }

    /** @test */
    public function it_can_can_get_bricks()
    {
        $brickable = new Class extends Brickable {
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
        $page->addBricks([[get_class($brickable), []], [get_class($brickable), []]]);
        $this->assertCount(2, Brick::all());
        $this->assertEmpty(Brick::all()->diff($page->getBricks()));
    }

    /** @test */
    public function it_can_can_get_bricks_from_brickable_type()
    {
        $brickable = new Class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        $otherBrickable = new Class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable), get_class($otherBrickable)]);
        $page = factory(Page::class)->create();
        $page->addBricks([[get_class($brickable)], [get_class($otherBrickable)]]);
        $this->assertCount(1, $page->getBricks([get_class($brickable)]));
        $this->assertEmpty(Brick::where('brickable_type', get_class($brickable))->get()
            ->diff($page->getBricks([get_class($brickable)])));
    }

    /** @test */
    public function it_can_can_get_brickable_types_bricks()
    {
        $brickable = new Class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        $otherBrickable = new Class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable), get_class($otherBrickable)]);
        $page = factory(Page::class)->create();
        $page->addBricks([[get_class($brickable)], [get_class($otherBrickable)]]);
        $this->assertCount(1, $page->getBricks([get_class($brickable)]));
        $this->assertEmpty(Brick::where('brickable_type', get_class($brickable))
            ->get()
            ->diff($page->getBricks([get_class($brickable)])));
    }

    /** @test */
    public function it_can_return_first_brick()
    {
        $brickable = new Class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        $otherBrickable = new Class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable), get_class($otherBrickable)]);
        $page = factory(Page::class)->create();
        $page->addBricks([
            [get_class($brickable), ['text' => 'Text #1']],
            [get_class($otherBrickable), ['text' => 'Text #2']],
            [get_class($brickable), ['text' => 'Text #3']],
        ]);
        $brick = $page->getFirstBrick();
        $this->assertTrue($brick->is(Brick::where('data->text', 'Text #1')->first()));
    }

    /** @test */
    public function it_can_return_first_brick_from_brickable_type()
    {
        $brickable = new Class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        $otherBrickable = new Class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable), get_class($otherBrickable)]);
        $page = factory(Page::class)->create();
        $page->addBricks([
            [get_class($brickable), ['text' => 'Text #1']],
            [get_class($otherBrickable), ['text' => 'Text #2']],
            [get_class($brickable), ['text' => 'Text #3']],
        ]);
        $brick = $page->getFirstBrick(get_class($otherBrickable));
        $this->assertTrue($brick->is(Brick::where('data->text', 'Text #2')->first()));
    }

    /** @test */
    public function it_can_return_readable_class_name()
    {
        $this->assertEquals(
            'Has one brickable with constraints model',
            (new HasOneBrickableWithConstraintsModel)->getReadableClassName()
        );
    }

    /** @test */
    public function it_can_clear_bricks()
    {
        $brickable = new Class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        $otherBrickable = new Class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable), get_class($otherBrickable)]);
        $page = factory(Page::class)->create();
        $page->addBricks([
            [get_class($brickable), ['text' => 'Text #1']],
            [get_class($brickable), ['text' => 'Text #2']],
            [get_class($otherBrickable), ['text' => 'Text #3']],
        ]);
        $this->assertEquals(3, Brick::count());
        $page->clearBricks();
        $bricks = Brick::all();
        $this->assertEquals(0, $bricks->count());
    }

    /** @test */
    public function it_can_clear_bricks_from_brickable_type()
    {
        $brickable = new Class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        $otherBrickable = new Class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable), get_class($otherBrickable)]);
        $page = factory(Page::class)->create();
        $page->addBricks([
            [get_class($brickable), ['text' => 'Text #1']],
            [get_class($brickable), ['text' => 'Text #2']],
            [get_class($otherBrickable), ['text' => 'Text #3']],
        ]);
        $this->assertEquals(3, Brick::count());
        $page->clearBricks([get_class($brickable)]);
        $bricks = Brick::all();
        $this->assertEquals(1, $bricks->count());
        $this->assertEquals(get_class($otherBrickable), $bricks->first()->brickable_type);
    }

    /** @test */
    public function it_can_clear_bricks_except()
    {
        $brickable = new Class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        $otherBrickable = new Class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable), get_class($otherBrickable)]);
        $page = factory(Page::class)->create();
        $page->addBricks([
            [get_class($brickable), ['text' => 'Text #1']],
            [get_class($brickable), ['text' => 'Text #2']],
            [get_class($otherBrickable), ['text' => 'Text #3']],
        ]);
        $this->assertEquals(3, Brick::count());
        $bricksToKeep = Brick::where('brickable_type', get_class($brickable))->get();
        $page->clearBricksExcept($bricksToKeep);
        $bricks = Brick::all();
        $this->assertEquals($bricksToKeep->count(), $bricks->count());
        $this->assertEquals($bricksToKeep, $bricks);
    }

    /** @test */
    public function it_can_check_if_model_can_add_brick_for_brickable_type()
    {
        $model = (new HasOneBrickableWithConstraintsModel)->create();
        $model->addBricks([[OneTextColumn::class], [OneTextColumn::class], [OneTextColumn::class]]);
        $this->assertFalse($model->canAddBricksFrom(OneTextColumn::class));
        Brick::first()->delete();
        $this->assertTrue($model->canAddBricksFrom(OneTextColumn::class));
    }

    /** @test */
    public function it_can_check_if_model_can_destroy_brick_for_brickable_type()
    {
        $model = (new HasOneBrickableWithConstraintsModel)->create();
        $model->addBricks([[OneTextColumn::class], [OneTextColumn::class], [OneTextColumn::class]]);
        $this->assertTrue($model->canDeleteBricksFrom(OneTextColumn::class));
        Brick::first()->delete();
        $this->assertTrue($model->canDeleteBricksFrom(OneTextColumn::class));
        Brick::first()->delete();
        $this->assertFalse($model->canDeleteBricksFrom(OneTextColumn::class));
    }

    /** @test */
    public function it_can_return_all_registered_brickables()
    {
        $model = (new HasOneBrickableWithConstraintsModel)->create();
        $brickableOne = new class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        $brickableTwo = new class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickableOne), get_class($brickableTwo)]);
        $registeredBrickables = $model->getRegisteredBrickables();
        $this->assertCount(count(config('brickables.registered')), $registeredBrickables);
    }

    /** @test */
    public function it_can_return_brickables_that_can_be_added_to_model()
    {
        $model = (new HasOneBrickableWithConstraintsModel)->create();
        $additionableBbrickables = $model->getAdditionableBrickables();
        $this->assertCount(count($model->brickables['canOnlyHandle']), $additionableBbrickables);
        $this->assertInstanceOf($model->brickables['canOnlyHandle'][0], $additionableBbrickables->first());
        $page = factory(Page::class)->create();
        $pageAditionnableBrickables = $page->getAdditionableBrickables();
        $this->assertCount(count(config('brickables.registered')), $pageAditionnableBrickables);
    }

    /** @test */
    public function it_can_display_model_bricks_html()
    {
        view()->addNamespace('laravel-brickables', 'tests/views');
        $brickable = new class extends Brickable {
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
        $page->addBrick(get_class($brickable), ['custom' => 'dummy']);
        $this->assertEquals(
            view('laravel-brickables::bricks', [
                'model' => $page,
                'brickableClasses' => [get_class($brickable)],
            ])->toHtml(),
            $page->displayBricks([get_class($brickable)])
        );
        $this->assertEquals(
            view('laravel-brickables::bricks', [
                'model' => $page,
                'brickableClasses' => [],
            ])->toHtml(),
            $page->displayBricks()
        );
    }

    /** @test */
    public function it_can_display_model_admin_panel_html()
    {
        Brickables::routes();
        view()->addNamespace('laravel-brickables', 'tests/views');
        $brickable = new class extends Brickable {
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
        $page->addBrick(get_class($brickable), ['custom' => 'dummy']);
        $this->assertEquals(
            view('laravel-brickables::admin.panel.layout', ['model' => $page])->toHtml(),
            $page->displayAdminPanel()
        );
    }
}
