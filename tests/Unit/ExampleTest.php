<?php

namespace Okipa\LaravelBrickable\Tests\Unit;

use Okipa\LaravelBrickable\Brickables\OneTextColumn;
use Okipa\LaravelBrickable\Brickables\TwoTextColumns;
use Okipa\LaravelBrickable\Tests\BrickableTestCase;
use Okipa\LaravelBrickable\Tests\Models\Page;

class ExampleTest extends BrickableTestCase
{
    /** @test */
    public function add_brick_returns_brick_instance()
    {
        $page = factory(Page::class)->create();
        $twoTextColumnsBrick = $page->addBrick(TwoTextColumns::class, [
            'left_content' => 'Left text content',
            'right_content' => 'Right text content',
        ]);
        $rawBrick = $page->bricks->first();
        $this->assertInstanceOf(TwoTextColumns::class, $twoTextColumnsBrick);
        $this->assertEquals($rawBrick->id, $twoTextColumnsBrick->id);
        $this->assertEquals($rawBrick->data['left_content'], $twoTextColumnsBrick->left_content);
        $this->assertEquals($rawBrick->data['right_content'], $twoTextColumnsBrick->right_content);
    }

    /** @test */
    public function get_bricks_returns_bricks_collection()
    {
        $page = factory(Page::class)->create();
        $oneTextColumnBrick = $page->addBrick(OneTextColumn::class, [
            'content' => 'Text content',
        ]);
        $twoTextColumnsBrick = $page->addBrick(TwoTextColumns::class, [
            'left_content' => 'Left text content',
            'right_content' => 'Right text content',
        ]);
        $bricks = $page->getBricks();
        $this->assertEquals($bricks, collect([$oneTextColumnBrick, $twoTextColumnsBrick]));
    }
}
