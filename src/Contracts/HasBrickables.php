<?php

namespace Okipa\LaravelBrickable\Contracts;

use Illuminate\Support\Collection;
use Okipa\LaravelBrickable\Models\Brick;

interface HasBrickables
{
    /**
     * Associate an array of brick to the model.
     *
     * @param array $bricks
     *
     * @return \Illuminate\Support\Collection
     * @throws \Okipa\LaravelBrickable\Exceptions\NonExistentBrickTypeException
     */
    public function addBricks(array $bricks): Collection;

    /**
     * Associate a brick to the model.
     *
     * @param string $brickType
     * @param array $data
     *
     * @return \Okipa\LaravelBrickable\Models\Brick
     * @throws \Okipa\LaravelBrickable\Exceptions\NonExistentBrickTypeException
     */
    public function addBrick(string $brickType, array $data): Brick;

    /**
     * Get first brick from given brick type.
     *
     * @param string $brickType
     *
     * @return \Okipa\LaravelBrickable\Models\Brick|null
     * @throws \Okipa\LaravelBrickable\Exceptions\NonExistentBrickTypeException
     */
    public function getFirstBrick(string $brickType): ?Brick;

    /**
     * Get the model associated bricks.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getBricks(): Collection;
}
