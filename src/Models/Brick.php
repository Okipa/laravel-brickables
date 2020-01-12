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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['brickable_type', 'data'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'json',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function brickable()
    {
        return $this->morphTo();
    }

    /**
     * @inheritDoc
     */
    public function toHtml(): string
    {
        return (string) view($this->brickable->getBrickViewPath(), $this->getAttribute('data'));
    }

    /**
     * @return \Okipa\LaravelBrickables\Abstracts\Brickable
     */
    public function getBrickableAttribute(): Brickable
    {
        return app($this->getAttribute('brickable_type'));
    }
}
