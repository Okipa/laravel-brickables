<?php

namespace Okipa\LaravelBrickables\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelBrickables\Contracts\HasBrickables;
use Okipa\LaravelBrickables\Traits\HasBrickablesTrait;

class Company extends Model
{
    /** @var string */
    protected $table = 'companies';

    /** @var array */
    protected $fillable = ['name'];
}
