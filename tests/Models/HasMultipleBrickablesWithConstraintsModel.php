<?php

namespace Okipa\LaravelBrickables\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelBrickables\Brickables\OneTextColumn;
use Okipa\LaravelBrickables\Brickables\TwoTextColumns;
use Okipa\LaravelBrickables\Contracts\HasBrickables;
use Okipa\LaravelBrickables\Traits\HasBrickablesTrait;

class HasMultipleBrickablesWithConstraintsModel extends Model implements HasBrickables
{
    use HasBrickablesTrait;

    public array $brickables = [
        'numberOfBricks' => [
            OneTextColumn::class => ['min' => 1, 'max' => 1],
            TwoTextColumns::class => ['max' => 1],
        ],
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'has_multiple_brickables_with_constraints_models';
}
