<?php

namespace Okipa\LaravelBrickables\Tests\Unit;

use Illuminate\Support\Facades\Route;
use Okipa\LaravelBrickables\Brickables\OneTextColumn;
use Okipa\LaravelBrickables\Tests\BrickableTestCase;
use Okipa\LaravelBrickables\Tests\Models\Page;

class BrickableTest extends BrickableTestCase
{
    /** @test */
    public function it_returns_brickable_label()
    {
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['content' => 'Text content']);
        $this->assertEquals((new OneTextColumn)->getLabel(), $brick->brickable->getLabel());
    }

    /** @test */
    public function it_returns_brickable_view_path()
    {
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['content' => 'Text content']);
        $this->assertEquals((new OneTextColumn)->getViewPath(), $brick->brickable->getViewPath());
    }

    /** @test */
    public function it_returns_brickable_edit_route()
    {
        Route::get('brick/edit/{brick}', function () {
            return;
        })->name('brick.edit');
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['content' => 'Text content']);
        $this->assertEquals((new OneTextColumn)->getEditRoute($brick), $brick->brickable->getEditRoute($brick));
    }

    /** @test */
    public function it_returns_brickable_destroy_route()
    {
        Route::delete('brick/destroy/{brick}', function () {
            return;
        })->name('brick.destroy');
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['content' => 'Text content']);
        $this->assertEquals((new OneTextColumn)->getDestroyRoute($brick), $brick->brickable->getDestroyRoute($brick));
    }
}
