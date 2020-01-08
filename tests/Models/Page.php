<?php

namespace Okipa\LaravelBrickable\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelBrickable\Traits\HasBrickables;

class Page extends Model
{
    use HasBrickables;

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
