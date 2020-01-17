<?php

namespace Okipa\LaravelBrickables\Tests\Unit;

use Okipa\LaravelBrickables\Brickables\OneTextColumn;
use Okipa\LaravelBrickables\Facades\Brickables;
use Okipa\LaravelBrickables\Tests\Brickables\Brickable;
use Okipa\LaravelBrickables\Tests\BrickableTestCase;
use Okipa\LaravelBrickables\Tests\Models\Page;

class BricksControllerTest extends BrickableTestCase
{
    /** @test */
    public function create_action_without_brickable_type_returns_validation_error()
    {
        Brickables::routes();
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
        Brickables::routes();
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
        Brickables::routes();
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
        Brickables::routes();
        $page = factory(Page::class)->create();
        $this->call('POST', 'brick/store', [
            'model_type' => Page::class,
            'model_id' => $page->id,
            'brickable_type' => OneTextColumn::class,
            'admin_panel_url' => 'admin-panel',
            'text' => 'Text',
        ])->assertRedirect('admin-panel');
        $brick = $page->getFirstBrick(OneTextColumn::class);
        $brick->data = json_encode($brick->data);
        $this->assertDatabaseHas('bricks', $brick->toArray());
    }

    /** @test */
    public function edit_action_displays_brickable_admin_view_with_data()
    {
        Brickables::routes();
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['text' => 'Text']);
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
        Brickables::routes();
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['text' => 'Text']);
        $this->call('POST', 'brick/update/' . $brick->id, [
            '_method' => 'PUT',
            'admin_panel_url' => 'admin-panel',
        ])->assertStatus(302)->assertSessionHas('errors');
    }

    /** @test */
    public function update_action_updates_brick_and_redirects_to_admin_panel()
    {
        Brickables::routes();
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['text' => 'Text']);
        $this->call('POST', 'brick/update/' . $brick->id, [
            '_method' => 'PUT',
            'admin_panel_url' => 'admin-panel',
            'text' => 'New text',
        ])->assertRedirect('admin-panel');
        $brick->data = json_encode(['text' => 'New text']);
        $this->assertDatabaseHas('bricks', $brick->toArray());
    }

    /** @test */
    public function destroy_action_destroys_brick_and_redirects_to_admin_panel()
    {
        Brickables::routes();
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['text' => 'Text']);
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
        Brickables::routes();
        $page = factory(Page::class)->create();
        $brickOne = $page->addBrick(OneTextColumn::class, ['text' => 'Text #1']);
        $brickTwo = $page->addBrick(OneTextColumn::class, ['text' => 'Text #2']);
        $brickThree = $page->addBrick(OneTextColumn::class, ['text' => 'Text #3']);
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
        Brickables::routes();
        $pageOne = factory(Page::class)->create();
        $pageTwo = factory(Page::class)->create();
        $brickOne = $pageOne->addBrick(OneTextColumn::class, ['text' => 'Text #1']);
        $pageTwo->addBrick(OneTextColumn::class, ['text' => 'Text #2']);
        $brickTwo = $pageOne->addBrick(OneTextColumn::class, ['text' => 'Text #3']);
        $pageTwo->addBrick(OneTextColumn::class, ['text' => 'Text #4']);
        $brickThree = $pageOne->addBrick(OneTextColumn::class, ['text' => 'Text #5']);
        $pageTwo->addBrick(OneTextColumn::class, ['text' => 'Text #6']);
        $this->assertEquals(1, $brickOne->position);
        $this->assertEquals(2, $brickTwo->position);
        $this->assertEquals(3, $brickThree->position);
        $this->call('POST', 'brick/move/down/' . $brickOne->id, ['admin_panel_url' => 'admin-panel'])
            ->assertRedirect('admin-panel');
        $this->assertEquals(1, $brickTwo->fresh()->position);
        $this->assertEquals(2, $brickOne->fresh()->position);
        $this->assertEquals(3, $brickThree->fresh()->position);
    }

    /** @test */
    public function it_uses_brickable_custom_controller()
    {
        Brickables::routes();
        $page = factory(Page::class)->create();
        view()->addNamespace('laravel-brickables', 'tests/views');
        $this->call('GET', 'brick/create', [
            'model_type' => Page::class,
            'model_id' => $page->id,
            'brickable_type' => Brickable::class,
            'admin_panel_url' => 'admin-panel',
        ])->assertOk()
            ->assertViewIs((new Brickable)->getFormViewPath())
            ->assertViewHas('data', null);
    }
}
