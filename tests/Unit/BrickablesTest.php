<?php

namespace Okipa\LaravelBrickables\Tests\Unit;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Okipa\LaravelBrickables\Abstracts\Brickable;
use Okipa\LaravelBrickables\Facades\Brickables;
use Okipa\LaravelBrickables\Models\Brick;
use Okipa\LaravelBrickables\Tests\BrickableTestCase;
use Okipa\LaravelBrickables\Tests\Models\BrickModel;
use Okipa\LaravelBrickables\Tests\Models\Page;

class BrickablesTest extends BrickableTestCase
{
    /** @test */
    public function it_only_displays_once_css_resources()
    {
        view()->addNamespace('laravel-brickables', 'tests/views');
        $brickableOne = new class extends Brickable {
            public function setBrickViewPath(): string
            {
                return 'laravel-brickables::brick-test';
            }

            protected function setCssResourcePath(): string
            {
                return 'my/test/css/path/brickable-one.css';
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
        $brickableTwo = new class extends Brickable {
            public function setBrickViewPath(): string
            {
                return 'laravel-brickables::brick-test';
            }

            protected function setCssResourcePath(): string
            {
                return 'my/test/css/path/brickable-two.css';
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
        config()->set('brickables.registered', [get_class($brickableOne), get_class($brickableTwo)]);
        $page = factory(Page::class)->create();
        $page->addBricks([
            [get_class($brickableOne), ['custom' => 'first-brickable-one']],
            [get_class($brickableTwo), ['custom' => 'first-brickable-two']],
            [get_class($brickableOne), ['custom' => 'second-brickable-one']],
            [get_class($brickableTwo), ['custom' => 'second-brickable-two']],
        ]);
        $html = view('laravel-brickables::page-test', compact('page', 'brickableOne', 'brickableTwo'))->toHtml();
        $this->assertStringNotContainsString($html, 'javascript');
        $headHtmlContent = Str::beforeLast(Str::after($html, '<head>'), '</head>');
        $this->assertEquals(1, substr_count($headHtmlContent, 'my/test/css/path/brickable-one.css'));
        $this->assertEquals(1, substr_count($headHtmlContent, 'my/test/css/path/brickable-two.css'));
    }

    /** @test */
    public function it_only_displays_once_js_resources()
    {
        view()->addNamespace('laravel-brickables', 'tests/views');
        $brickableOne = new class extends Brickable {
            public function setBrickViewPath(): string
            {
                return 'laravel-brickables::brick-test';
            }

            protected function setJsResourcePath(): string
            {
                return 'my/test/js/path/brickable-one.js';
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
        $brickableTwo = new class extends Brickable {
            public function setBrickViewPath(): string
            {
                return 'laravel-brickables::brick-test';
            }

            protected function setJsResourcePath(): string
            {
                return 'my/test/js/path/brickable-two.js';
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
        config()->set('brickables.registered', [get_class($brickableOne), get_class($brickableTwo)]);
        $page = factory(Page::class)->create();
        $page->addBricks([
            [get_class($brickableOne), ['custom' => 'first-brickable-one']],
            [get_class($brickableTwo), ['custom' => 'first-brickable-two']],
            [get_class($brickableOne), ['custom' => 'second-brickable-one']],
            [get_class($brickableTwo), ['custom' => 'second-brickable-two']],
        ]);
        $html = view('laravel-brickables::page-test', compact('page', 'brickableOne', 'brickableTwo'))->toHtml();
        $bodyHtmlContent = Str::beforeLast(Str::after($html, '<body>'), '</body>');
        $this->assertStringNotContainsString($html, 'css');
        $this->assertEquals(1, substr_count($bodyHtmlContent, 'my/test/js/path/brickable-one.js'));
        $this->assertEquals(1, substr_count($bodyHtmlContent, 'my/test/js/path/brickable-two.js'));
    }

    /** @test */
    public function it_can_display_model_bricks_admin_panel_html()
    {
        view()->addNamespace('laravel-brickables', 'tests/views');
        $brickable = new class extends Brickable {
            public function setBrickViewPath(): string
            {
                return 'laravel-brickables::brick-test';
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
        Brickables::routes();
        $page = factory(Page::class)->create();
        $page->addBrick(get_class($brickable), ['custom' => 'dummy']);
        $this->assertEquals(
            view('laravel-brickables::admin.panel.layout', ['model' => $page])->render(),
            $page->displayAdminPanel()
        );
    }

    /** @test */
    public function it_can_cast_bricks_to_their_brickable_related_brick_model()
    {
        $brickableOne = new class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        $brickableTwo = new class extends Brickable {
            protected function setBrickModelClass(): string
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
        config()->set('brickables.registered', [get_class($brickableOne), get_class($brickableTwo)]);
        $page = factory(Page::class)->create();
        $page->addBricks([[get_class($brickableOne)], [get_class($brickableTwo)]]);
        $bricks = Brick::all();
        $this->assertEquals(
            Brick::class,
            $bricks->where('brickable_type', get_class($brickableOne))->first()->getMorphClass()
        );
        $this->assertEquals(
            Brick::class,
            $bricks->where('brickable_type', get_class($brickableTwo))->first()->getMorphClass()
        );
        $bricks = Brickables::castBricks($bricks);
        $this->assertCount(2, $bricks);
        $this->assertInstanceOf(Brick::class, $bricks->where('brickable_type', get_class($brickableOne))->first());
        $this->assertInstanceOf(BrickModel::class, $bricks->where('brickable_type', get_class($brickableTwo))->first());
    }

    /** @test */
    public function it_can_cast_bricks_and_return_them_in_correct_order()
    {
        $brickableOne = new class extends Brickable {
            public function validateStoreInputs(): array
            {
                return [];
            }

            public function validateUpdateInputs(): array
            {
                return [];
            }
        };
        $brickableTwo = new class extends Brickable {
            protected function setBrickModelClass(): string
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
        config()->set('brickables.registered', [get_class($brickableOne), get_class($brickableTwo)]);
        $page = factory(Page::class)->create();
        $page->addBrick(get_class($brickableOne));
        $page->addBrick(get_class($brickableTwo));
        $page->addBrick(get_class($brickableOne));
        $page->addBrick(get_class($brickableTwo));
        $bricks = Brickables::castBricks(Brick::all());
        $this->assertEquals(['1', '2', '3', '4'], $bricks->pluck('position')->toArray());
    }

    /** @test */
    public function it_can_cast_brick_to_its_brickable_related_brick_model()
    {
        $brickable = new class extends Brickable {
            protected function setBrickModelClass(): string
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
        config()->set('brickables.registered', [get_class($brickable)]);
        $page = factory(Page::class)->create();
        $page->addBricks([[get_class($brickable), []]]);
        $brick = Brick::first();
        $this->assertEquals(
            Brick::class,
            $brick->where('brickable_type', get_class($brickable))->first()->getMorphClass()
        );
        $brick = Brickables::castBrick($brick);
        $this->assertInstanceOf(BrickModel::class, $brick->where('brickable_type', get_class($brickable))->first());
    }

    /** @test */
    public function it_can_get_model_from_create_request()
    {
        $page = factory(Page::class)->create();
        $request = (new Request)->merge(['model_type' => $page->getMorphClass(), 'model_id' => $page->id]);
        $model = Brickables::getModelFromRequest($request);
        $this->assertTrue($page->is($model));
    }

    /** @test */
    public function it_can_get_model_from_edit_request()
    {
        $brickable = new class extends Brickable {
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
        $request = (new Request)->merge(['brick' => $brick]);
        $model = Brickables::getModelFromRequest($request);
        $this->assertTrue($page->is($model));
    }
}
