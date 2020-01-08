<?php

namespace Okipa\LaravelBrickable\Models;

use Illuminate\Database\Eloquent\Model;

class Brick extends Model
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
}
