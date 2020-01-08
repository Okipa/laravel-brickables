<?php

namespace Okipa\LaravelBrickable\Brickables;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelBrickable\Contracts\Brickable;

class TwoTextColumns extends Model implements Brickable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['left_content', 'right_content'];
}
