<?php

namespace Okipa\LaravelBrickable\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelBrickable\Contracts\HasBrickables;
use Okipa\LaravelBrickable\Traits\HasBrickablesTrait;

class Page extends Model implements HasBrickables
{
    use HasBrickablesTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug'];
}
