<?php

namespace Okipa\LaravelBrickables\Tests\Unit;

use Okipa\LaravelBrickables\Abstracts\Brickable;
use Okipa\LaravelBrickables\Exceptions\InvalidBrickableClassException;
use Okipa\LaravelBrickables\Exceptions\NotRegisteredBrickableClassException;
use Okipa\LaravelBrickables\Models\Brick;
use Okipa\LaravelBrickables\Tests\BrickableTestCase;
use Okipa\LaravelBrickables\Tests\Models\BrickModel;
use Okipa\LaravelBrickables\Tests\Models\HasBrickablesModel;
use Okipa\LaravelBrickables\Tests\Models\Page;

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
            protected function setValidationRules(): array
            {
                return [];
            }
        };
        $page = factory(Page::class)->create();
        $this->expectException(NotRegisteredBrickableClassException::class);
        $page->addBrick(get_class($brickable), []);
    }

    /** @test */
    public function it_can_add_brick()
    {
        $brickable = new Class extends Brickable {
            protected function setValidationRules(): array
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
            protected function setValidationRules(): array
            {
                return [];
            }
        };
        config()->set('brickables.defaultBrickModel', get_class($brickModel));
        config()->set('brickables.registered', [get_class($brickable)]);
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(get_class($brickable), []);
        $this->assertTrue($brick->is($brickModel->first()));
        $this->assertEquals('dummy', $brick->dummy());
    }

    /** @test */
    public function it_can_add_bricks()
    {
        $brickable = new Class extends Brickable {
            protected function setValidationRules(): array
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
    public function it_can_get_bricks_with_different_brickable_models()
    {
        $brickableOne = new Class extends Brickable {
            protected function setValidationRules(): array
            {
                return [];
            }
        };
        $brickableTwo = new Class extends Brickable {
            protected function setBrickModelClass(): string
            {
                return BrickModel::class;
            }

            protected function setValidationRules(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickableOne), get_class($brickableTwo)]);
        $page = factory(Page::class)->create();
        $page->addBricks([[get_class($brickableOne), []], [get_class($brickableTwo), []]]);
        $this->assertCount(2, $page->getBricks());
        $this->assertEquals(
            Brick::class,
            $page->getBricks()->where('brickable_type', get_class($brickableOne))->first()->getMorphClass()
        );
        $this->assertEquals(
            BrickModel::class,
            $page->getBricks()->where('brickable_type', get_class($brickableTwo))->first()->getMorphClass()
        );
        $this->assertEmpty(Brick::all()->diff($page->getBricks()));
    }

    /** @test */
    public function it_returns_first_brick()
    {
        $brickable = new Class extends Brickable {
            protected function setValidationRules(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable)]);
        $page = factory(Page::class)->create();
        $page->addBricks([
            [get_class($brickable), ['text' => 'Text #1']],
            [get_class($brickable), ['text' => 'Text #2']],
            [get_class($brickable), ['text' => 'Text #3']],
        ]);
        $brick = $page->getFirstBrick(get_class($brickable));
        $this->assertTrue($brick->is(Brick::where('data->text', 'Text #1')->first()));
    }

    /** @test */
    public function it_returns_readable_class_name()
    {
        $this->assertEquals('Has brickables model', (new HasBrickablesModel)->getReadableClassName());
    }
}
