<?php

namespace Okipa\LaravelBrickables\Tests\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Okipa\LaravelBrickables\Models\Brick;

class BrickModelWithCompanyRelationShip extends Brick
{
    /** @var array */
    protected $with = ['companies'];

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_brick', 'brick_id');
    }
}
