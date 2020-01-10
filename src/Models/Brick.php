<?php

namespace Okipa\LaravelBrickables\Models;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
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
        return (string) view($this->getViewPath(), $this->getAttribute('data'));
    }

    /**
     * Get view path from the related brick type.
     *
     * @return string
     */
    public function getViewPath(): string
    {
        return Brickables::getType($this->getAttribute('brickable_type'))['view'];
    }

    /**
     * Get label from the related brick type.
     *
     * @return string
     */
    public function getLabel(): string
    {
        return Brickables::getType($this->getAttribute('brickable_type'))['label'];
    }
}
