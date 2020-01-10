<?php

namespace Okipa\LaravelBrickable\Tests\Unit;

use Okipa\LaravelBrickable\Facades\Brickables;
use Okipa\LaravelBrickable\Tests\BrickableTestCase;
use Okipa\LaravelBrickable\Tests\Models\Page;

class BrickablesTest extends BrickableTestCase
{
    /** @test */
    public function it_returns_available_brickable_types()
    {
        $this->assertEquals(config('brickables.types'), Brickables::getTypes());
    }

    /** @test */
    public function it_returns_brickable_type()
    {
        $this->assertEquals(config('brickables.types.oneTextColumn'), Brickables::getType('oneTextColumn'));
    }

    /** @test */
    public function it_displays_model_bricks_html()
    {
        $page = factory(Page::class)->create();
        $html = '';
        $html .= $page->addBrick('oneTextColumn', ['content' => 'Text content'])->toHtml();
        $html .= $page->addBrick('twoTextColumns', [
            'left_content' => 'Left text',
            'right_content' => 'Right text',
        ])->toHtml();
        $this->assertEquals($html, Brickables::display($page)->toHtml());
    }

    /** @test */
    public function it_displays_model_bricks_admin_panel_html()
    {
        $page = factory(Page::class)->create();
        $this->assertEquals(
            view('laravel-brickable::admin-panel', ['model' => $page]),
            Brickables::adminPanel($page)->toHtml()
        );
    }
}
