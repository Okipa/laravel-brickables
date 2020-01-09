<?php

namespace Okipa\LaravelBrickable\Tests\Unit;

use Okipa\LaravelBrickable\Brickables\OneTextColumn;
use Okipa\LaravelBrickable\Brickables\TwoTextColumns;
use Okipa\LaravelBrickable\Exceptions\InvalidBrickTypeException;
use Okipa\LaravelBrickable\Tests\BrickableTestCase;
use Okipa\LaravelBrickable\Tests\Models\Page;

class HasBrickableTest extends BrickableTestCase
{
    /** @test */
    public function it_cannot_add_wrong_typed_brick()
    {
        $page = factory(Page::class)->create();
        $this->expectException(InvalidBrickTypeException::class);
        $page->addBrick(Page::class, []);
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
        config()->set('brickable.brick_model', \Okipa\LaravelBrickable\Tests\Models\Brick::class);
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['content' => 'Text content']);
        $this->assertTrue($brick->is($page->bricks->first()));
        $this->assertEquals('fake-view-path', $brick->getViewPath());
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

    /** @test */
    public function it_renders_a_brick_html()
    {
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['content' => 'Text content']);
        $this->assertEquals(view($brick->getViewPath(), $brick->data), $brick->toHtml());
    }

    /** @test */
    public function it_renders_a_bricks_collection_html()
    {
        $page = factory(Page::class)->create();
        $html = '';
        $html .= $page->addBrick(OneTextColumn::class, ['content' => 'Text content'])->toHtml();
        $html .= $page->addBrick(
            TwoTextColumns::class,
            ['left_content' => 'Left text', 'right_content' => 'Right text']
        )->toHtml();
        $this->assertEquals($html, $page->displayBricks());
    }
}
