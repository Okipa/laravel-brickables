<?php

namespace Okipa\LaravelBrickable\Models;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Brickable extends Model implements Htmlable
{

    /**
     * @inheritDoc
     */
    protected $casts = [
        //
    ];

    /**
     * @inheritDoc
     */
    public function toHtml()
    {
        // TODO: Implement toHtml() method.
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
