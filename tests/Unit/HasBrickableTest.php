<?php

namespace Okipa\LaravelBrickable\Tests\Unit;

use Okipa\LaravelBrickable\Exceptions\NonExistentBrickTypeException;
use Okipa\LaravelBrickable\Tests\BrickableTestCase;
use Okipa\LaravelBrickable\Tests\Models\Brick;
use Okipa\LaravelBrickable\Tests\Models\Page;

class HasBrickableTest extends BrickableTestCase
{
    /** @test */
    public function it_cannot_add_with_non_existent_brick_type()
    {
        $page = factory(Page::class)->create();
        $this->expectException(NonExistentBrickTypeException::class);
        $page->addBrick('none', []);
    }

    /** @test */
    public function it_can_add_brick()
    {
        $page = factory(Page::class)->create();
        $brick = $page->addBrick('oneTextColumn', ['content' => 'Text content']);
        $this->assertTrue($brick->is($page->bricks->first()));
    }

    /** @test */
    public function it_can_add_brick_with_a_custom_brick_model()
    {
        config()->set('brickable.model', Brick::class);
        $page = factory(Page::class)->create();
        $brick = $page->addBrick('oneTextColumn', ['content' => 'Text content']);
        $this->assertTrue($brick->is($page->bricks->first()));
        $this->assertEquals('fake-view-path', $brick->getViewPath());
    }

    /** @test */
    public function it_can_add_bricks()
    {
        $page = factory(Page::class)->create();
        $bricks = $page->addBricks([
            ['oneTextColumn', ['content' => 'Text content']],
            ['twoTextColumns', ['left_content' => 'Left text', 'right_content' => 'Right text']],
        ]);
        $this->assertCount(2, $bricks);
        $this->assertEmpty($page->bricks->diff($bricks));
    }

    /** @test */
    public function it_can_get_bricks()
    {
        $page = factory(Page::class)->create();
        $page->addBricks([
            ['oneTextColumn', ['content' => 'Text content']],
            ['twoTextColumns', ['left_content' => 'Left text', 'right_content' => 'Right text']],
        ]);
        $this->assertCount(2, $page->getBricks());
        $this->assertEmpty($page->bricks->diff($page->getBricks()));
    }

    /** @test */
    public function it_returns_first_brick()
    {
        $page = factory(Page::class)->create();
        $page->addBricks([
            ['twoTextColumns', ['left_content' => 'Left text', 'right_content' => 'Right text']],
            ['oneTextColumn', ['content' => 'Text content #1']],
            ['oneTextColumn', ['content' => 'Text content #2']],
        ]);
        $brick = $page->getFirstBrick('oneTextColumn');
        $this->assertTrue($brick->is($page->bricks()->where('data->content', 'Text content #1')->first()));
    }

    /** @test */
    public function it_renders_a_brick_html()
    {
        $page = factory(Page::class)->create();
        $brick = $page->addBrick('oneTextColumn', ['content' => 'Text content']);
        $this->assertEquals(view($brick->getViewPath(), $brick->data), $brick->toHtml());
    }

    /** @test */
    public function it_renders_a_bricks_collection_html()
    {
        $page = factory(Page::class)->create();
        $html = '';
        $html .= $page->addBrick('oneTextColumn', ['content' => 'Text content'])->toHtml();
        $html .= $page->addBrick('twoTextColumns', [
            'left_content' => 'Left text',
            'right_content' => 'Right text',
        ])->toHtml();
        $this->assertEquals($html, $page->displayBricks());
    }

    /** @test */
    public function it_returns_available_brickable_types()
    {
        $this->assertEquals(config('brickable.types'), Brick::getTypes());
    }
}
