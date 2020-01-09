<?php

namespace Okipa\LaravelBrickable\Tests\Models;

use Illuminate\Contracts\Support\Htmlable;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Brick extends \Okipa\LaravelBrickable\Models\Brick
{
    /**
     * @inheritDoc
     */
    public function getViewPath(): string
    {
        return 'fake-view-path';
    }
}
