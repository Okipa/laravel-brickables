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
    public function it_returns_brickable_template_view_path()
    {
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['content' => 'Text content']);
        $this->assertEquals((new OneTextColumn)->getBrickViewPath(), $brick->brickable->getBrickViewPath());
    }

    /** @test */
    public function it_returns_brickable_admin_view_path()
    {
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['content' => 'Text content']);
        $this->assertEquals((new OneTextColumn)->getFormViewPath(), $brick->brickable->getFormViewPath());
    }

    /** @test */
    public function it_returns_brickable_store_route()
    {
        Route::post('brick/store', function () {
            return;
        })->name('brick.store');
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['content' => 'Text content']);
        $this->assertEquals((new OneTextColumn)->getStoreRoute(), $brick->brickable->getStoreRoute());
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
    public function it_returns_brickable_update_route()
    {
        Route::put('brick/update/{brick}', function () {
            return;
        })->name('brick.update');
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['content' => 'Text content']);
        $this->assertEquals((new OneTextColumn)->getUpdateRoute($brick), $brick->brickable->getUpdateRoute($brick));
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
