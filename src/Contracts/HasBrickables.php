<?php

namespace Okipa\LaravelBrickables\Contracts;

use Illuminate\Support\Collection;
use Okipa\LaravelBrickables\Models\Brick;

interface HasBrickables
{
    /**
     * @param array $bricksData
     *
     * @return \Illuminate\Support\Collection
     * @throws \Okipa\LaravelBrickables\Exceptions\BrickableCannotBeHandledException
     * @throws \Okipa\LaravelBrickables\Exceptions\InvalidBrickableClassException
     * @throws \Okipa\LaravelBrickables\Exceptions\NotRegisteredBrickableClassException
     */
    public function addBricks(array $bricksData): Collection;

    /**
     * @param string $brickableClass
     * @param array $data
     *
     * @return \Okipa\LaravelBrickables\Models\Brick
     * @throws \Okipa\LaravelBrickables\Exceptions\BrickableCannotBeHandledException
     * @throws \Okipa\LaravelBrickables\Exceptions\InvalidBrickableClassException
     * @throws \Okipa\LaravelBrickables\Exceptions\NotRegisteredBrickableClassException
     */
    public function addBrick(string $brickableClass, array $data = []): Brick;

    public function getFirstBrick(?string $brickableClass = null): ?Brick;

    public function getBricks(?string $brickableClass = null): Collection;

    public function getReadableClassName(): string;

    public function clearBricksExcept(string $brickableClass, Collection $excludeBricks): void;

    public function clearBricks(?string $brickableClass = null): void;

    /**
     * @param string $brickableClass
     *
     * @throws \Okipa\LaravelBrickables\Exceptions\BrickableCannotBeHandledException
     */
    public function checkBrickableCanBeHandled(string $brickableClass): void;

    /**
     * @param string $brickableClass
     *
     * @throws \Okipa\LaravelBrickables\Exceptions\InvalidBrickableClassException
     */
    public function checkBrickableTypeIsInstanceOfBrickable(string $brickableClass): void;

    public function canDeleteBricksFrom(string $brickableClass): bool;

    public function canAddBricksFrom(string $brickableClass): bool;

    public function canHandle(string $brickableClass): bool;
}
