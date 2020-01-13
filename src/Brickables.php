<?php

namespace Okipa\LaravelBrickables;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Okipa\LaravelBrickables\Contracts\HasBrickables;
use Okipa\LaravelBrickables\Controllers\BricksController;
use Okipa\LaravelBrickables\Middleware\CheckBrickableRequest;

class Brickables implements Htmlable
{
    /** @property string $html */
    protected $html;

    /**
     * Get the available brickables.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll(): Collection
    {
        $brickables = new Collection;
        foreach (config('brickables.registered') as $brickableClass) {
            $brickables->push(app($brickableClass));
        }

        return $brickables;
    }

    /**
     * Display all the model-related content bricks html at once.
     *
     * @param \Okipa\LaravelBrickables\Contracts\HasBrickables $model
     *
     * @return $this
     */
    public function bricks(HasBrickables $model): self
    {
        $this->html = view('laravel-brickables::bricks', ['model' => $model]);

        return $this;
    }

    /**
     * Display the model-related content bricks admin panel html.
     *
     * @param \Okipa\LaravelBrickables\Contracts\HasBrickables $model
     *
     * @return $this
     */
    public function adminPanel(HasBrickables $model): self
    {
        $this->html = view('laravel-brickables::admin.panel', ['model' => $model]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toHtml()
    {
        return (string) $this->html;
    }

    /**
     * Register the brickables CRUD routes.
     */
    public function routes(): void
    {
        Route::middleware(CheckBrickableRequest::class)->group(function () {
            Route::get('brick/create', [BricksController::class, 'create'])->name('brick.create');
            Route::post('brick/store', [BricksController::class, 'store'])->name('brick.store');
            Route::get('brick/edit/{brick}', [BricksController::class, 'edit'])->name('brick.edit');
            Route::put('brick/update/{brick}', [BricksController::class, 'update'])->name('brick.update');
            Route::delete('brick/destroy/{brick}', [BricksController::class, 'destroy'])->name('brick.destroy');
        });
    }
}
