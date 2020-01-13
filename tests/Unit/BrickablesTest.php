<?php

namespace Okipa\LaravelBrickables\Tests\Unit;

use Illuminate\Support\Collection;
use Okipa\LaravelBrickables\Abstracts\Brickable;
use Okipa\LaravelBrickables\Facades\Brickables;
use Okipa\LaravelBrickables\Tests\BrickableTestCase;
use Okipa\LaravelBrickables\Tests\Models\Page;

class BrickablesTest extends BrickableTestCase
{
    /** @test */
    public function it_returns_all_registered_brickables()
    {
        $brickable = new Class extends Brickable {
            public function setValidationRules(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable)]);
        $brickables = Brickables::getAll();
        $this->assertCount(count(config('brickables.registered')), $brickables);
        $this->assertInstanceOf(Collection::class, $brickables);
    }

    /** @test */
    public function it_displays_model_bricks_html()
    {
        view()->addNamespace('laravel-brickables', 'tests/views');
        $brickable = new Class extends Brickable {
            public function setBrickViewPath(): string
            {
                return 'laravel-brickables::dummy';
            }

            public function setValidationRules(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable)]);
        $page = factory(Page::class)->create();
        $brick = $page->addBrick(get_class($brickable), ['data' => 'dummy']);
        $this->assertEquals($brick->toHtml(), Brickables::bricks($page)->toHtml());
    }

    /** @test */
    public function it_displays_model_bricks_admin_panel_html()
    {
        view()->addNamespace('laravel-brickables', 'tests/views');
        $brickable = new Class extends Brickable {
            public function setBrickViewPath(): string
            {
                return 'laravel-brickables::dummy';
            }

            public function setValidationRules(): array
            {
                return [];
            }
        };
        config()->set('brickables.registered', [get_class($brickable)]);
        Brickables::routes();
        $page = factory(Page::class)->create();
        $page->addBrick(get_class($brickable), ['data' => 'dummy']);
        $this->assertEquals(
            view('laravel-brickables::admin.panel', ['model' => $page]),
            Brickables::adminPanel($page)->toHtml()
        );
    }
}
