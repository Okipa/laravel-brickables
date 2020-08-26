<?php

namespace Okipa\LaravelBrickables\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelBrickables\Brickables\OneTextColumn;
use Okipa\LaravelBrickables\Contracts\HasBrickables;
use Okipa\LaravelBrickables\Traits\HasBrickablesTrait;

class HasOneBrickableWithConstraintsModel extends Model implements HasBrickables
{
    use HasBrickablesTrait;

    public array $brickables = [
        'can_only_handle' => [OneTextColumn::class],
        'number_of_bricks' => [OneTextColumn::class => ['min' => 1, 'max' => 3]],
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'has_one_brickable_with_constraints_models';
}
