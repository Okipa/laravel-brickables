<?php

namespace Okipa\LaravelBrickables\Tests\Unit;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Okipa\LaravelBrickables\Brickables\OneTextColumn;
use Okipa\LaravelBrickables\Brickables\TwoTextColumns;
use Okipa\LaravelBrickables\Facades\Brickables;
use Okipa\LaravelBrickables\Tests\BrickableTestCase;
use Okipa\LaravelBrickables\Tests\Models\Page;

class BrickablesTest extends BrickableTestCase
{
    /** @test */
    public function it_returns_all_available_brickables()
    {
        $brickables = Brickables::getAll();
        $this->assertCount(count(config('brickables.registered')), $brickables);
        $this->assertInstanceOf(Collection::class, $brickables);
    }

    /** @test */
    public function it_displays_model_bricks_html()
    {
        $page = factory(Page::class)->create();
        $html = '';
        $html .= $page->addBrick(OneTextColumn::class, ['content' => 'Text content'])->toHtml();
        $html .= $page->addBrick(TwoTextColumns::class, [
            'left_content' => 'Left text',
            'right_content' => 'Right text',
        ])->toHtml();
        $this->assertEquals($html, Brickables::display($page)->toHtml());
    }

    /** @test */
    public function it_displays_model_bricks_admin_panel_html()
    {
        Route::get('brick/edit/{brick}', function () {
            return;
        })->name('brick.edit');
        Route::delete('brick/destroy/{brick}', function () {
            return;
        })->name('brick.destroy');
        $page = factory(Page::class)->create();
        $page->addBrick(OneTextColumn::class, ['content' => 'Text content']);
        $this->assertEquals(
            view('laravel-brickables::admin-panel', ['model' => $page]),
            Brickables::adminPanel($page)->toHtml()
        );
    }
}
