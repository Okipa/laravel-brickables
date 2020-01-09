<?php

namespace Okipa\LaravelBrickable\Models;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
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
    protected $fillable = ['brick_type', 'data'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'json',
    ];

    /**
     * Get available brick types.
     *
     * @return array
     */
    public static function getTypes(): array
    {
        return array_map(function ($type) {
            $type['label'] = __($type['label']);

            return $type;
        }, config('brickable.types'));
    }

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
        return config('brickable.types.' . $this->getAttribute('brick_type') . '.view');
    }
}
