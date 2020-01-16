<?php

namespace Okipa\LaravelBrickables\Tests\Unit;

use Okipa\LaravelBrickables\Abstracts\Brickable;
use Okipa\LaravelBrickables\Exceptions\InvalidBrickableClassException;
use Okipa\LaravelBrickables\Exceptions\NotRegisteredBrickableClassException;
use Okipa\LaravelBrickables\Models\Brick;
use Okipa\LaravelBrickables\Tests\BrickableTestCase;
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
            public function setValidationRules(): array
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
            public function setValidationRules(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable)]);
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(get_class($brickable), []);
        $this->assertTrue($brick->is($page->bricks->first()));
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
            public function setValidationRules(): array
            {
                return [];
            }
        };
        config()->set('brickables.brickModel', get_class($brickModel));
        config()->set('brickables.registered', [get_class($brickable)]);
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(get_class($brickable), []);
        $this->assertTrue($brick->is($page->bricks->first()));
        $this->assertEquals('dummy', $brick->dummy());
    }

    /** @test */
    public function it_can_add_bricks()
    {
        $brickable = new Class extends Brickable {
            public function setValidationRules(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable)]);
        $page = factory(Page::class)->create();
        $bricks = $page->addBricks([
            [get_class($brickable), []],
            [get_class($brickable), []],
        ]);
        $this->assertCount(2, $bricks);
        $this->assertEmpty($page->bricks->diff($bricks));
    }

    /** @test */
    public function it_can_get_bricks()
    {
        $brickable = new Class extends Brickable {
            public function setValidationRules(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable)]);
        $page = factory(Page::class)->create();
        $page->addBricks([
            [get_class($brickable), []],
            [get_class($brickable), []],
        ]);
        $this->assertCount(2, $page->getBricks());
        $this->assertEmpty($page->bricks->diff($page->getBricks()));
    }

    /** @test */
    public function it_returns_first_brick()
    {
        $brickable = new Class extends Brickable {
            public function setValidationRules(): array
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
        $this->assertTrue($brick->is($page->bricks()->where('data->text', 'Text #1')->first()));
    }
}
