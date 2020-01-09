<?php

namespace Okipa\LaravelBrickable\Models;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class Brick extends Model implements Htmlable
{
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
        return app($this->getAttribute('brick_type'))->getViewPath();
    }
}
