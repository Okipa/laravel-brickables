<?php

namespace Okipa\LaravelBrickables\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelBrickables\Brickables\OneTextColumn;
use Okipa\LaravelBrickables\Contracts\HasBrickables;
use Okipa\LaravelBrickables\Traits\HasBrickablesTrait;

class HasBrickablesModel extends Model implements HasBrickables
{
    use HasBrickablesTrait;

    protected $hasSingleBrick = [OneTextColumn::class];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'has_brickables_models';
}
