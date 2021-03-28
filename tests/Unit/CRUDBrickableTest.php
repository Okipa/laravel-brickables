<?php

namespace Okipa\LaravelBrickables\Tests\Unit;

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Okipa\LaravelBrickables\Abstracts\Brickable;
use Okipa\LaravelBrickables\Brickables\OneTextColumn;
use Okipa\LaravelBrickables\Brickables\TwoTextColumns;
use Okipa\LaravelBrickables\Contracts\HasBrickables;
use Okipa\LaravelBrickables\Middleware\CRUDBrickable;
use Okipa\LaravelBrickables\Tests\BrickableTestCase;
use Okipa\LaravelBrickables\Tests\Models\HasOneConstrainedBrickableModel;
use Okipa\LaravelBrickables\Tests\Models\ModelWithoutBrickables;
use Okipa\LaravelBrickables\Tests\Models\Page;

class CRUDBrickableTest extends BrickableTestCase
{
    /** @test */
    public function request_without_brick_without_model_type_is_forbidden(): void
    {
        Route::get('/', function () {
            //
        })->middleware(CRUDBrickable::class);
        $response = $this->call('GET', '/')->assertForbidden();
        self::assertEquals('The model_type value is missing from the request.', $response->exception->getMessage());
    }

    /** @test */
    public function request_without_brick_with_wrong_model_type_is_forbidden(): void
    {
        Route::get('/', function () {
            //
        })->middleware(CRUDBrickable::class);
        $response = $this->call('GET', '/', ['model_type' => ModelWithoutBrickables::class])->assertForbidden();
        self::assertEquals(
            'The ' . ModelWithoutBrickables::class . ' class should implement ' . HasBrickables::class . '.',
            $response->exception->getMessage()
        );
    }

    /** @test */
    public function request_without_brick_without_model_id_is_forbidden(): void
    {
        Route::get('/', function () {
            //
        })->middleware(CRUDBrickable::class);
        $response = $this->call('GET', '/', ['model_type' => Page::class])->assertForbidden();
        self::assertEquals('The model_id value is missing from the request.', $response->exception->getMessage());
    }

    /** @test */
    public function request_without_brick_with_wrong_brickable_type_is_forbidden(): void
    {
        Route::get('/', function () {
            //
        })->middleware([CRUDBrickable::class]);
        $response = $this->call('GET', '/', [
            'model_type' => Page::class,
            'model_id' => 1,
            'brickable_type' => Page::class,
        ])->assertForbidden();
        self::assertEquals(
            'The given ' . Page::class . ' brickable class should extend ' . Brickable::class . '.',
            $response->exception->getMessage()
        );
    }

    /** @test */
    public function request_without_brick_with_brickable_that_cannot_be_handled_by_model_is_forbidden(): void
    {
        Route::get('/', function () {
            //
        })->middleware(CRUDBrickable::class);
        $response = $this->call('GET', '/', [
            'model_type' => HasOneConstrainedBrickableModel::class,
            'model_id' => 1,
            'brickable_type' => TwoTextColumns::class,
        ])->assertForbidden();
        self::assertEquals(
            'The given ' . TwoTextColumns::class . ' brickable cannot be handled by the '
            . HasOneConstrainedBrickableModel::class
            . ' Eloquent model.',
            $response->exception->getMessage()
        );
    }

    /** @test */
    public function request_without_brick_without_admin_panel_url_is_forbidden(): void
    {
        Route::get('/', function () {
            //
        })->middleware(CRUDBrickable::class);
        $response = $this->call('GET', '/', [
            'model_type' => Page::class,
            'model_id' => 1,
            'brickable_type' => OneTextColumn::class,
        ])->assertForbidden();
        self::assertEquals(
            'The admin_panel_url value is missing from the request.',
            $response->exception->getMessage()
        );
    }

    /** @test */
    public function request_without_brick_is_ok(): void
    {
        Route::get('/', function () {
            //
        })->middleware(CRUDBrickable::class);
        $this->call('GET', '/', [
            'model_type' => Page::class,
            'model_id' => 1,
            'brickable_type' => OneTextColumn::class,
            'admin_panel_url' => 'url',
        ])->assertOk();
    }

    /** @test */
    public function request_with_brick_without_admin_panel_url_is_forbidden(): void
    {
        Route::get('/{brick}', function () {
            //
        })->middleware(SubstituteBindings::class, CRUDBrickable::class);
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['text' => 'Text']);
        $response = $this->call('GET', '/' . $brick->id)->assertForbidden();
        self::assertEquals(
            'The admin_panel_url value is missing from the request.',
            $response->exception->getMessage()
        );
    }

    /** @test */
    public function request_with_brick_is_ok(): void
    {
        Route::get('/{brick}', function () {
            //
        })->middleware(SubstituteBindings::class, CRUDBrickable::class);
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(OneTextColumn::class, ['text' => 'Text']);
        $this->call('GET', '/' . $brick->id, ['admin_panel_url' => 'url'])->assertOk();
    }
}
