<?php

namespace Okipa\LaravelBrickables\Contracts;

use Illuminate\Support\Collection;
use Okipa\LaravelBrickables\Models\Brick;

interface HasBrickables
{
    /**
     * Associate an array of brick to the model.
     *
     * @param array $bricks
     *
     * @return \Illuminate\Support\Collection
     * @throws \Okipa\LaravelBrickables\Exceptions\InvalidBrickableClassException
     * @throws \Okipa\LaravelBrickables\Exceptions\NotRegisteredBrickableClassException
     * @throws \Okipa\LaravelBrickables\Exceptions\BrickableCannotBeHandledException
     */
    public function addBricks(array $bricks): Collection;

    /**
     * Associate a brick to the model.
     *
     * @param string $brickType
     * @param array $data
     *
     * @return \Okipa\LaravelBrickables\Models\Brick
     * @throws \Okipa\LaravelBrickables\Exceptions\InvalidBrickableClassException
     * @throws \Okipa\LaravelBrickables\Exceptions\NotRegisteredBrickableClassException
     * @throws \Okipa\LaravelBrickables\Exceptions\BrickableCannotBeHandledException
     */
    public function addBrick(string $brickType, array $data): Brick;

    /**
     * Get first brick from given brick type.
     *
     * @param string|null $brickableClass
     *
     * @return \Okipa\LaravelBrickables\Models\Brick|null
     */
    public function getFirstBrick(?string $brickableClass = null): ?Brick;

    /**
     * Get the model associated bricks.
     *
     * @param string|null $brickableClass
     *
     * @return \Illuminate\Support\Collection
     */
    public function getBricks(?string $brickableClass = null): Collection;

    /**
     * Get the model name readable for a human.
     *
     * @return string
     */
    public function getReadableClassName(): string;

    /**
     * Clear all bricks from a given brickable type except excluded bricks.
     *
     * @param string $brickableClass
     * @param \Illuminate\Support\Collection $excludeBricks
     *
     * @return void
     */
    public function clearBricksExcept(string $brickableClass, Collection $excludeBricks): void;

    /**
     * Clear all bricks from a given brickable type.
     *
     * @param string|null $brickableClass
     *
     * @return void
     */
    public function clearBricks(?string $brickableClass = null): void;

    /**
     * Check if the brickable type can be handled by the model.
     *
     * @param string $brickableClass
     *
     * @throws \Okipa\LaravelBrickables\Exceptions\BrickableCannotBeHandledException
     */
    public function checkBrickableCanBeHandled(string $brickableClass): void;

    /**
     * Check if the brickable type correctly extends the correct abstract class.
     *
     * @param string $brickableClass
     *
     * @throws \Okipa\LaravelBrickables\Exceptions\InvalidBrickableClassException
     */
    public function checkBrickableTypeIsInstanceOfBrickable(string $brickableClass): void;

    /**
     * Check if model can remove bricks from the given brickable type.
     *
     * @param string $brickableClass
     *
     * @return bool
     */
    public function canDeleteBricksFrom(string $brickableClass): bool;

    /**
     * Check if model can add bricks from the given brickable type.
     *
     * @param string $brickableClass
     *
     * @return bool
     */
    public function canAddBricksFrom(string $brickableClass): bool;

    /**
     * Check if the the model is allowed to handle the given brickable.
     *
     * @param string $brickableClass
     *
     * @return bool
     */
    public function canHandle(string $brickableClass): bool;
}
