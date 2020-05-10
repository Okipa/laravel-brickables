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
use Okipa\LaravelBrickables\Models\Brick;
use Okipa\LaravelBrickables\Tests\BrickableTestCase;
use Okipa\LaravelBrickables\Tests\Models\HasBrickablesModel;
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
            protected function setStoreValidationRules(): array
            {
                return [];
            }

            protected function setUpdateValidationRules(): array
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
            protected function setStoreValidationRules(): array
            {
                return [];
            }

            protected function setUpdateValidationRules(): array
            {
                return [];
            }
        };
        $model = (new HasBrickablesModel)->create();
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
        $this->assertTrue($model->isAllowedToHandle(OneTextColumn::class));
        $this->assertFalse($model->isAllowedToHandle(TwoTextColumns::class));
    }

    /** @test */
    public function it_can_add_brick()
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
            protected function setStoreValidationRules(): array
            {
                return [];
            }

            protected function setUpdateValidationRules(): array
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
    public function it_can_add_brick_with_max_number_of_bricks_defined()
    {
        $model = (new HasBrickablesModel)->create();
        $brickOne = $model->addBrick(OneTextColumn::class);
        $brickTwo = $model->addBrick(OneTextColumn::class);
        $model->addBrick(OneTextColumn::class);
        $brickFour = $model->addBrick(OneTextColumn::class);
        $bricks = Brick::all();
        $this->assertFalse($brickOne->is($bricks->first()));
        $this->assertTrue($brickTwo->is($bricks->first()));
        $this->assertTrue($brickFour->is($bricks->last()));
    }

    /** @test */
    public function it_can_add_bricks()
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
        $bricks = $page->addBricks([[get_class($brickable), []], [get_class($brickable), []]]);
        $this->assertCount(2, $bricks);
        $this->assertEmpty(Brick::all()->diff($bricks));
    }

    /** @test */
    public function it_can_can_get_bricks()
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
        $page->addBricks([[get_class($brickable), []], [get_class($brickable), []]]);
        $this->assertCount(2, $page->getBricks());
        $this->assertEmpty(Brick::all()->diff($page->getBricks()));
    }

    /** @test */
    public function it_can_can_get_bricks_from_brickable_type()
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
        $otherBrickable = new Class extends Brickable {
            protected function setStoreValidationRules(): array
            {
                return [];
            }

            protected function setUpdateValidationRules(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable), get_class($otherBrickable)]);
        $page = factory(Page::class)->create();
        $page->addBricks([[get_class($brickable)], [get_class($otherBrickable)]]);
        $this->assertCount(1, $page->getBricks(get_class($brickable)));
        $this->assertEmpty(Brick::where('brickable_type', get_class($brickable))->get()
            ->diff($page->getBricks(get_class($brickable))));
    }

    /** @test */
    public function it_can_can_get_brickable_types_bricks()
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
        $otherBrickable = new Class extends Brickable {
            protected function setStoreValidationRules(): array
            {
                return [];
            }

            protected function setUpdateValidationRules(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable), get_class($otherBrickable)]);
        $page = factory(Page::class)->create();
        $page->addBricks([[get_class($brickable)], [get_class($otherBrickable)]]);
        $this->assertCount(1, $page->getBricks(get_class($brickable)));
        $this->assertEmpty(Brick::where('brickable_type', get_class($brickable))
            ->get()
            ->diff($page->getBricks(get_class($brickable))));
    }

    /** @test */
    public function it_can_return_first_brick()
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
        $otherBrickable = new Class extends Brickable {
            protected function setStoreValidationRules(): array
            {
                return [];
            }

            protected function setUpdateValidationRules(): array
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
            protected function setStoreValidationRules(): array
            {
                return [];
            }

            protected function setUpdateValidationRules(): array
            {
                return [];
            }
        };
        $otherBrickable = new Class extends Brickable {
            protected function setStoreValidationRules(): array
            {
                return [];
            }

            protected function setUpdateValidationRules(): array
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
        $this->assertEquals('Has brickables model', (new HasBrickablesModel)->getReadableClassName());
    }

    /** @test */
    public function it_can_clear_bricks()
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
        $otherBrickable = new Class extends Brickable {
            protected function setStoreValidationRules(): array
            {
                return [];
            }

            protected function setUpdateValidationRules(): array
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
            protected function setStoreValidationRules(): array
            {
                return [];
            }

            protected function setUpdateValidationRules(): array
            {
                return [];
            }
        };
        $otherBrickable = new Class extends Brickable {
            protected function setStoreValidationRules(): array
            {
                return [];
            }

            protected function setUpdateValidationRules(): array
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
        $page->clearBricks(get_class($brickable));
        $bricks = Brick::all();
        $this->assertEquals(1, $bricks->count());
        $this->assertEquals(get_class($otherBrickable), $bricks->first()->brickable_type);
    }

    /** @test */
    public function it_can_clear_bricks_except()
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
        $otherBrickable = new Class extends Brickable {
            protected function setStoreValidationRules(): array
            {
                return [];
            }

            protected function setUpdateValidationRules(): array
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
        $page->clearBricksExcept(
            get_class($brickable),
            collect()->push(Brick::where('brickable_type', get_class($brickable))->first())
        );
        $bricks = Brick::all();
        $this->assertEquals(2, $bricks->count());
        $this->assertEquals(get_class($brickable), $bricks->first()->brickable_type);
        $this->assertEquals(get_class($otherBrickable), $bricks->last()->brickable_type);
    }

    /** @test */
    public function it_can_check_if_model_can_add_brick_for_brickable_type()
    {
        $model = (new HasBrickablesModel)->create();
        $model->addBricks([[OneTextColumn::class], [OneTextColumn::class], [OneTextColumn::class]]);
        $this->assertFalse($model->canAddBricksFrom(OneTextColumn::class));
        Brick::first()->delete();
        $this->assertTrue($model->canAddBricksFrom(OneTextColumn::class));
    }

    /** @test */
    public function it_can_check_if_model_can_destroy_brick_for_brickable_type()
    {
        $model = (new HasBrickablesModel)->create();
        $model->addBricks([[OneTextColumn::class], [OneTextColumn::class], [OneTextColumn::class]]);
        $this->assertTrue($model->canDeleteBricksFrom(OneTextColumn::class));
        Brick::first()->delete();
        $this->assertTrue($model->canDeleteBricksFrom(OneTextColumn::class));
        Brick::first()->delete();
        $this->assertFalse($model->canDeleteBricksFrom(OneTextColumn::class));
    }
}
