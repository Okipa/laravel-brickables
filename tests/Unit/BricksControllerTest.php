<?php

namespace Okipa\LaravelBrickables\Tests\Unit;

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Okipa\LaravelBrickables\Brickables\OneTextColumn;
use Okipa\LaravelBrickables\Controllers\BricksController;
use Okipa\LaravelBrickables\Middleware\CRUDBrickable;
use Okipa\LaravelBrickables\Tests\BrickableTestCase;
use Okipa\LaravelBrickables\Tests\Models\Page;

class BricksControllerTest extends BrickableTestCase
{
    /** @test */
    public function create_action_without_brickable_type_returns_validation_error()
    {
        Route::get('brick/create', [BricksController::class, 'create'])->middleware(CRUDBrickable::class);
        $page = factory(Page::class)->create();
        $this->call('GET', 'brick/create', [
            'model_type' => Page::class,
            'model_id' => $page->id,
            'admin_panel_url' => 'admin-panel',
        ])->assertStatus(302)->assertSessionHas('errors');
    }

    /** @test */
    public function create_action_displays_brickable_admin_view_with_data()
    {
        Route::get('brick/create', [BricksController::class, 'create'])->middleware(CRUDBrickable::class);
        Route::post('/', function () {
        })->name('brick.store');
        $page = factory(Page::class)->create();
        $this->call('GET', 'brick/create', [
            'model_type' => Page::class,
            'model_id' => $page->id,
            'brickable_type' => OneTextColumn::class,
            'admin_panel_url' => 'admin-panel',
        ])->assertOk()
            ->assertViewIs((new OneTextColumn)->getFormViewPath())
            ->assertViewHas('brick', null)
            ->assertViewHas('model', $page)
            ->assertViewHas('brickable', (new OneTextColumn))
            ->assertViewHas('adminPanelUrl', 'admin-panel');
    }

    /** @test */
    public function store_action_with_wrong_data_returns_validation_error()
    {
        Route::post('brick/store', [BricksController::class, 'store'])->middleware(CRUDBrickable::class);
        $page = factory(Page::class)->create();
        $this->call('POST', 'brick/store', [
            'model_type' => Page::class,
            'model_id' => $page->id,
            'brickable_type' => OneTextColumn::class,
            'admin_panel_url' => 'admin-panel',
        ])->assertStatus(302)->assertSessionHas('errors');
    }

    /** @test */
    public function store_action_stores_new_brick_and_redirects_to_admin_panel()
    {
        Route::post('brick/store', [BricksController::class, 'store'])->middleware(CRUDBrickable::class);
        $page = factory(Page::class)->create();
        $this->call('POST', 'brick/store', [
            'model_type' => Page::class,
            'model_id' => $page->id,
            'brickable_type' => OneTextColumn::class,
            'admin_panel_url' => 'admin-panel',
            'content' => 'Text content',
        ])->assertRedirect('admin-panel');
        $brick = $page->getFirstBrick(OneTextColumn::class);
        $brick->data = json_encode($brick->data);
        $this->assertDatabaseHas('bricks', $brick->toArray());
    }

    /** @test */
    public function edit_action_displays_brickable_admin_view_with_data()
    {
        Route::get('brick/edit/{brick}', [BricksController::class, 'edit'])
            ->middleware(SubstituteBindings::class, CRUDBrickable::class);
        Route::post('/', function () {
        })->name('brick.update');
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['content' => 'Text content']);
        $this->call('GET', 'brick/edit/' . $brick->id, ['admin_panel_url' => 'admin-panel'])
            ->assertOk()
            ->assertViewIs((new OneTextColumn)->getFormViewPath())
            ->assertViewHas('brick', $brick)
            ->assertViewHas('model', $brick->model)
            ->assertViewHas('brickable', $brick->brickable)
            ->assertViewHas('adminPanelUrl', 'admin-panel');
    }

    /** @test */
    public function update_action_with_wrong_data_returns_validation_error()
    {
        Route::put('brick/update/{brick}', [BricksController::class, 'update'])
            ->middleware(SubstituteBindings::class, CRUDBrickable::class);
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['content' => 'Text content']);
        $this->call('POST', 'brick/update/' . $brick->id, [
            '_method' => 'PUT',
            'admin_panel_url' => 'admin-panel',
        ])->assertStatus(302)->assertSessionHas('errors');
    }

    /** @test */
    public function update_action_updates_brick_and_redirects_to_admin_panel()
    {
        Route::put('brick/update/{brick}', [BricksController::class, 'update'])
            ->middleware(SubstituteBindings::class, CRUDBrickable::class);
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['content' => 'Text content']);
        $this->call('POST', 'brick/update/' . $brick->id, [
            '_method' => 'PUT',
            'admin_panel_url' => 'admin-panel',
            'content' => 'New text content',
        ])->assertRedirect('admin-panel');
        $brick->data = json_encode(['content' => 'New text content']);
        $this->assertDatabaseHas('bricks', $brick->toArray());
    }

    /** @test */
    public function destroy_action_destroys_brick_and_redirects_to_admin_panel()
    {
        Route::delete('brick/destroy/{brick}', [BricksController::class, 'destroy'])
            ->middleware(SubstituteBindings::class, CRUDBrickable::class);
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['content' => 'Text content']);
        $this->call('POST', 'brick/destroy/' . $brick->id, [
            '_method' => 'DELETE',
            'admin_panel_url' => 'admin-panel',
        ])->assertRedirect('admin-panel');
        $brick->data = json_encode($brick->data);
        $this->assertDatabaseMissing('bricks', $brick->toArray());
    }

    /** @test */
    public function move_up_action_moves_up_brick_and_redirects_to_admin_panel()
    {
        Route::post('brick/move/up/{brick}', [BricksController::class, 'moveUp'])
            ->middleware(SubstituteBindings::class, CRUDBrickable::class);
        $page = factory(Page::class)->create();
        $brickOne = $page->addBrick(OneTextColumn::class, ['content' => 'Text #1']);
        $brickTwo = $page->addBrick(OneTextColumn::class, ['content' => 'Text #2']);
        $brickThree = $page->addBrick(OneTextColumn::class, ['content' => 'Text #3']);
        $this->assertEquals(1, $brickOne->position);
        $this->assertEquals(2, $brickTwo->position);
        $this->assertEquals(3, $brickThree->position);
        $this->call('POST', 'brick/move/up/' . $brickThree->id, ['admin_panel_url' => 'admin-panel'])
            ->assertRedirect('admin-panel');
        $this->assertEquals(1, $brickOne->fresh()->position);
        $this->assertEquals(2, $brickThree->fresh()->position);
        $this->assertEquals(3, $brickTwo->fresh()->position);
    }

    /** @test */
    public function move_down_action_moves_down_brick_and_redirects_to_admin_panel()
    {
        Route::post('brick/move/down/{brick}', [BricksController::class, 'moveDown'])
            ->middleware(SubstituteBindings::class, CRUDBrickable::class);
        $pageOne = factory(Page::class)->create();
        $pageTwo = factory(Page::class)->create();
        $brickOne = $pageOne->addBrick(OneTextColumn::class, ['content' => 'Text #1']);
        $pageTwo->addBrick(OneTextColumn::class, ['content' => 'Text #2']);
        $brickTwo = $pageOne->addBrick(OneTextColumn::class, ['content' => 'Text #3']);
        $pageTwo->addBrick(OneTextColumn::class, ['content' => 'Text #4']);
        $brickThree = $pageOne->addBrick(OneTextColumn::class, ['content' => 'Text #5']);
        $pageTwo->addBrick(OneTextColumn::class, ['content' => 'Text #6']);
        $this->assertEquals(1, $brickOne->position);
        $this->assertEquals(2, $brickTwo->position);
        $this->assertEquals(3, $brickThree->position);
        $this->call('POST', 'brick/move/down/' . $brickOne->id, ['admin_panel_url' => 'admin-panel'])
            ->assertRedirect('admin-panel');
        $this->assertEquals(1, $brickTwo->fresh()->position);
        $this->assertEquals(2, $brickOne->fresh()->position);
        $this->assertEquals(3, $brickThree->fresh()->position);
    }
}
