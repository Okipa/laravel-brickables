<?php

namespace Okipa\LaravelBrickables\Models;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelBrickables\Abstracts\Brickable;
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
    public $sortable = [
        'order_column_name' => 'position',
        'sort_when_creating' => true,
    ];

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }

    /** @inheritDoc */
    public function toHtml(): string
    {
        return (string) view($this->brickable->getBrickViewPath(), ['brick' => $this]);
    }

    /**
     * @return \Okipa\LaravelBrickables\Abstracts\Brickable
     */
    public function getBrickableAttribute(): Brickable
    {
        return (new $this->brickable_type);
    }

    /**
     * Build the sort query from the spatie/eloquent-sortable package.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function buildSortQuery()
    {
        return static::query()->where('model_type', $this->model_type)->where('model_id', $this->model_id);
    }
}
