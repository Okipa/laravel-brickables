<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelBrickables\Contracts\HasBrickables;
use Okipa\LaravelBrickables\Traits\HasBrickablesTrait;
use Tests\Database\Factories\PageFactory;

class Page extends Model implements HasBrickables
{
    use HasFactory;
    use HasBrickablesTrait;

    /** @var string */
    protected $table = 'pages';

    /** @var array<int, string> */
    protected $fillable = ['slug'];

    protected static function newFactory(): PageFactory
    {
        return PageFactory::new();
    }
}
