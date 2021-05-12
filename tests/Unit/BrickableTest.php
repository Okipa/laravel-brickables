<?php

namespace Okipa\LaravelBrickables\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Okipa\LaravelBrickables\Abstracts\Brickable;
use Okipa\LaravelBrickables\Tests\BrickableTestCase;
use Okipa\LaravelBrickables\Tests\Models\BrickModel;
use Okipa\LaravelBrickables\Tests\Models\Page;

class BrickableTest extends BrickableTestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_set_and_return_model(): void
    {
        $brickable = new class extends Brickable {
            public function setBrickModelClass(): string
            {
                return BrickModel::class;
            }

            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        self::assertEquals(BrickModel::class, $brickable->getBrickModel()->getMorphClass());
    }

    /** @test */
    public function it_can_set_and_return_label(): void
    {
        $brickable = new class extends Brickable {
            public function setLabel(): string
            {
                return 'Dummy label';
            }

            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        self::assertEquals('Dummy label', $brickable->getLabel());
    }

    /** @test */
    public function it_can_set_and_return_brick_view_path(): void
    {
        $brickable = new class extends Brickable {
            public function setBrickViewPath(): string
            {
                return 'dummy.brick.view.path';
            }

            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        self::assertEquals('dummy.brick.view.path', $brickable->getBrickViewPath());
    }

    /** @test */
    public function it_can_set_and_return_form_view_path(): void
    {
        $brickable = new class extends Brickable {
            public function setFormViewPath(): string
            {
                return 'dummy.form.view.path';
            }

            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        self::assertEquals('dummy.form.view.path', $brickable->getFormViewPath());
    }

    /** @test */
    public function it_can_set_and_return_store_route(): void
    {
        Route::post('dummy/store', function () {
        })->name('dummy.store');
        $brickable = new class extends Brickable {
            public function setStoreRouteName(): string
            {
                return 'dummy.store';
            }

            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        self::assertEquals('http://localhost/dummy/store', $brickable->getStoreRoute());
    }

    /** @test */
    public function it_can_set_and_return_edit_route(): void
    {
        Route::get('dummy/edit/{brick}', function () {
        })->name('dummy.edit');
        $brickable = new class extends Brickable {
            public function setEditRouteName(): string
            {
                return 'dummy.edit';
            }

            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable)]);
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(get_class($brickable), []);
        self::assertEquals('http://localhost/dummy/edit/' . $brick->id, $brickable->getEditRoute($brick));
    }

    /** @test */
    public function it_can_set_and_return_update_route(): void
    {
        Route::put('dummy/update/{brick}', static function () {
        })->name('dummy.update');
        $brickable = new class extends Brickable {
            public function setUpdateRouteName(): string
            {
                return 'dummy.update';
            }

            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable)]);
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(get_class($brickable), []);
        self::assertEquals('http://localhost/dummy/update/' . $brick->id, $brickable->getUpdateRoute($brick));
    }

    /** @test */
    public function it_can_set_and_return_destroy_route(): void
    {
        Route::delete('dummy/destroy/{brick}', function () {
        })->name('dummy.destroy');
        $brickable = new class extends Brickable {
            public function setDestroyRouteName(): string
            {
                return 'dummy.destroy';
            }

            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable)]);
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(get_class($brickable), []);
        self::assertEquals('http://localhost/dummy/destroy/' . $brick->id, $brickable->getDestroyRoute($brick));
    }

    /** @test */
    public function it_can_set_and_return_move_up_route(): void
    {
        Route::post('dummy/move/up/{brick}', function () {
            //
        })->name('dummy.move.up');
        $brickable = new class extends Brickable {
            public function setMoveUpRouteName(): string
            {
                return 'dummy.move.up';
            }

            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable)]);
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(get_class($brickable), []);
        self::assertEquals('http://localhost/dummy/move/up/' . $brick->id, $brickable->getMoveUpRoute($brick));
    }

    /** @test */
    public function it_can_set_and_return_move_down_route(): void
    {
        Route::post('dummy/move/down/{brick}', function () {
            //
        })->name('dummy.move.down');
        $brickable = new class extends Brickable {
            public function setMoveDownRouteName(): string
            {
                return 'dummy.move.down';
            }

            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable)]);
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(get_class($brickable), []);
        self::assertEquals('http://localhost/dummy/move/down/' . $brick->id, $brickable->getMoveDownRoute($brick));
    }

    /** @test */
    public function it_can_validate_inputs_and_return_validated_fields(): void
    {
        request()->merge(['text' => 'test']);
        $brickable = new class extends Brickable {
            public function validateStoreInputs(): array
            {
                return request()->validate(['text' => ['required', 'string']]);
            }

            public function validateUpdateInputs(): array
            {
                return request()->validate(['text' => ['required', 'string']]);
            }
        };
        self::assertEquals(['text' => 'test'], $brickable->validateStoreInputs());
        self::assertEquals(['text' => 'test'], $brickable->validateUpdateInputs());
    }

    /** @test */
    public function it_can_set_and_return_css_path(): void
    {
        $brickable = new class extends Brickable {
            protected function setCssResourcePath(): string
            {
                return 'my/test/css/path.css';
            }

            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        self::assertEquals('my/test/css/path.css', $brickable->getCssResourcePath());
    }

    /** @test */
    public function it_can_set_and_return_javascript_path(): void
    {
        $brickable = new class extends Brickable {
            protected function setJsResourcePath(): string
            {
                return 'my/test/javascript/path.css';
            }

            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        self::assertEquals('my/test/javascript/path.css', $brickable->getJsResourcePath());
    }
}
