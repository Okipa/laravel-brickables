<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelBrickables\Brickables\OneTextColumn;
use Okipa\LaravelBrickables\Brickables\TwoTextColumns;
use Okipa\LaravelBrickables\Contracts\HasBrickables;
use Okipa\LaravelBrickables\Traits\HasBrickablesTrait;

class HasMultipleConstrainedBrickablesModel extends Model implements HasBrickables
{
    use HasBrickablesTrait;

    public array $brickables = [
        'number_of_bricks' => [
            OneTextColumn::class => ['min' => 1, 'max' => 1],
            TwoTextColumns::class => ['max' => 1],
        ],
    ];

    /** @var string */
    protected $table = 'has_multiple_constrained_brickables_models';
}
