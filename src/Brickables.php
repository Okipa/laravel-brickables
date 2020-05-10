<?php

namespace Okipa\LaravelBrickables;

use Closure;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Okipa\LaravelBrickables\Abstracts\Brickable;
use Okipa\LaravelBrickables\Contracts\HasBrickables;
use Okipa\LaravelBrickables\Controllers\DispatchController;
use Okipa\LaravelBrickables\Middleware\CRUDBrickable;
use Okipa\LaravelBrickables\Models\Brick;

class Brickables implements Htmlable
{
    protected string $html;

    protected array $displayed = [];

    public function addToDisplayed(Brickable $brickable)
    {
        $this->displayed[] = $brickable;
    }

    public function getCssResourcesToLoad(): Collection
    {
        return $this->getDisplayed()
            ->map(fn(Brickable $brickable) => $brickable->getCssResourcePath())
            ->unique()
            ->filter();
    }

    protected function getDisplayed()
    {
        return collect($this->displayed)->unique();
    }

    public function getJsResourcesToLoad(): Collection
    {
        return $this->getDisplayed()
            ->map(fn(Brickable $brickable) => $brickable->getJsResourcePath())
            ->unique()
            ->filter();
    }

    public function displayBricks(HasBrickables $model, ?string $brickableClass = null): self
    {
        $this->html = view('laravel-brickables::bricks', compact('model', 'brickableClass'));

        return $this;
    }

    public function displayAdminPanel(HasBrickables $model): self
    {
        $this->html = view('laravel-brickables::admin.panel.layout', compact('model'));

        return $this;
    }

    public function toHtml()
    {
        return (string) $this->html;
    }

    public function routes(Closure $additionalRoutes = null): void
    {
        Route::middleware([
            CRUDBrickable::class,
            SubstituteBindings::class,
        ])->group(function () use ($additionalRoutes) {
            Route::get('brick/create', [DispatchController::class, 'create'])->name('brick.create');
            Route::post('brick/store', [DispatchController::class, 'store'])->name('brick.store');
            Route::get('brick/edit/{brick}', [DispatchController::class, 'edit'])->name('brick.edit');
            Route::put('brick/update/{brick}', [DispatchController::class, 'update'])->name('brick.update');
            Route::delete('brick/destroy/{brick}', [DispatchController::class, 'destroy'])->name('brick.destroy');
            Route::post('brick/move/up/{brick}', [DispatchController::class, 'moveUp'])->name('brick.move.up');
            Route::post('brick/move/down/{brick}', [DispatchController::class, 'moveDown'])->name('brick.move.down');
            if ($additionalRoutes) {
                $additionalRoutes();
            }
        });
    }

    public function getModelFromRequest(Request $request = null): ?HasBrickables
    {
        $request = $request ?: request();
        if ($request->has('model_type') && $request->has('model_id')) {
            $model = app($request->model_type);

            return $model->find($request->model_id);
        }
        if ($request->brick) {
            return $this->castBrick($request->brick)->model;
        }

        return null;
    }

    public function castBrick(Brick $brick): Brick
    {
        return $this->castBricks(collect()->push($brick))->first();
    }

    public function castBricks(Collection $bricks): Collection
    {
        if ($bricks->isEmpty()) {
            return $bricks;
        }
        $casted = new Collection;
        foreach ($bricks->pluck('brickable_type')->unique() as $brickableClass) {
            /** @var \Okipa\LaravelBrickables\Abstracts\Brickable $brickable */
            $brickable = app($brickableClass);
            /** @var \Okipa\LaravelBrickables\Models\Brick $model */
            $model = $brickable->getBrickModel();
            $brickableBricksDataArray = $bricks->where('brickable_type', $brickableClass)->map(function ($brick) {
                return $brick->getAttributes();
            })->toArray();
            $castedBricks = $model->hydrate($brickableBricksDataArray);
            $casted->push($castedBricks);
        }
        /** @var \Okipa\LaravelBrickables\Models\Brick $brickModel */
        $brickModel = app(config('brickables.bricks.model'));
        $orderColumnName = $brickModel->sortable['order_column_name'];

        return $casted->flatten()->sortBy($orderColumnName);
    }

    public function getAdditionableTo(HasBrickables $model): Collection
    {
        return $this->getAll()->filter(function ($brickable) use ($model) {
            $brickableClass = get_class($brickable);

            return $model->isAllowedToHandle($brickableClass) && $model->canAddBricksFrom($brickableClass);
        });
    }

    public function getAll(): Collection
    {
        $brickables = new Collection;
        foreach (config('brickables.registered') as $brickableClass) {
            /** @var Brickable $brickable */
            $brickable = app($brickableClass);
            $brickables->push($brickable);
        }

        return $brickables;
    }
}
