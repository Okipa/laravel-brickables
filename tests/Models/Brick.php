<?php

namespace Okipa\LaravelBrickables\Tests\Models;

use Illuminate\Contracts\Support\Htmlable;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Brick extends \Okipa\LaravelBrickables\Models\Brick
{
    /**
     * @inheritDoc
     */
    public function getViewPath(): string
    {
        return 'fake-view-path';
    }
}
