<?php

namespace Okipa\LaravelBrickables\Models;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Okipa\LaravelBrickables\Abstracts\Brickable;
use Okipa\LaravelBrickables\Facades\Brickables;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Brick extends Model implements Htmlable, Sortable
{
    use SortableTrait;

    /**
     * The spatie/eloquent-sortable trait configuration.
     *
     * @var array
     */
    public array $sortable = ['order_column_name' => 'position', 'sort_when_creating' => true];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bricks';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['data' => 'json'];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function toHtml(): string
    {
        Brickables::addToDisplayed($this->brickable);

        return (string) view($this->brickable->getBrickViewPath(), ['brick' => $this]);
    }

    public function getBrickableAttribute(): Brickable
    {
        return (new $this->brickable_type);
    }

    /**
     * Build the sort query from the spatie/eloquent-sortable package.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function buildSortQuery(): Builder
    {
        return static::query()->where('model_type', $this->model_type)->where('model_id', $this->model_id);
    }

    public function delete(): ?bool
    {
        if ($this->model->canDeleteBricksFrom($this->brickable_type)) {
            return parent::delete();
        }

        return false;
    }
}
