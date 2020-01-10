<?php

namespace Okipa\LaravelBrickables\Tests\Unit;

use Okipa\LaravelBrickables\Brickables\OneTextColumn;
use Okipa\LaravelBrickables\Brickables\TwoTextColumns;
use Okipa\LaravelBrickables\Exceptions\InvalidBrickableClassException;
use Okipa\LaravelBrickables\Exceptions\NotRegisteredBrickableClassException;
use Okipa\LaravelBrickables\Tests\Brickables\TestBrickable;
use Okipa\LaravelBrickables\Tests\BrickableTestCase;
use Okipa\LaravelBrickables\Tests\Models\Brick;
use Okipa\LaravelBrickables\Tests\Models\Page;

class HasBrickablesTest extends BrickableTestCase
{
    /** @test */
    public function it_cannot_add_invalid_brickable_class()
    {
        $page = factory(Page::class)->create();
        $this->expectException(InvalidBrickableClassException::class);
        $page->addBrick(Page::class, []);
    }

    /** @test */
    public function it_cannot_add_not_registered_brickable_class()
    {
        $page = factory(Page::class)->create();
        $this->expectException(NotRegisteredBrickableClassException::class);
        $page->addBrick(TestBrickable::class, []);
    }

    /** @test */
    public function it_can_add_brick()
    {
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['content' => 'Text content']);
        $this->assertTrue($brick->is($page->bricks->first()));
    }

    /** @test */
    public function it_can_add_brick_with_a_custom_brick_model()
    {
        config()->set('brickables.brickModel', Brick::class);
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['content' => 'Text content']);
        $this->assertTrue($brick->is($page->bricks->first()));
        $this->assertEquals('fake-view-path', $brick->getBrickableViewPath());
    }

    /** @test */
    public function it_can_add_bricks()
    {
        $page = factory(Page::class)->create();
        $bricks = $page->addBricks([
            [OneTextColumn::class, ['content' => 'Text content']],
            [TwoTextColumns::class, ['left_content' => 'Left text', 'right_content' => 'Right text']],
        ]);
        $this->assertCount(2, $bricks);
        $this->assertEmpty($page->bricks->diff($bricks));
    }

    /** @test */
    public function it_can_get_bricks()
    {
        $page = factory(Page::class)->create();
        $page->addBricks([
            [OneTextColumn::class, ['content' => 'Text content']],
            [TwoTextColumns::class, ['left_content' => 'Left text', 'right_content' => 'Right text']],
        ]);
        $this->assertCount(2, $page->getBricks());
        $this->assertEmpty($page->bricks->diff($page->getBricks()));
    }

    /** @test */
    public function it_returns_first_brick()
    {
        $page = factory(Page::class)->create();
        $page->addBricks([
            [TwoTextColumns::class, ['left_content' => 'Left text', 'right_content' => 'Right text']],
            [OneTextColumn::class, ['content' => 'Text content #1']],
            [OneTextColumn::class, ['content' => 'Text content #2']],
        ]);
        $brick = $page->getFirstBrick(OneTextColumn::class);
        $this->assertTrue($brick->is($page->bricks()->where('data->content', 'Text content #1')->first()));
    }
}
