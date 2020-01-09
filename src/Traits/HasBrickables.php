<?php

namespace Okipa\LaravelBrickable\Traits;

use Illuminate\Support\Collection;
use Okipa\LaravelBrickable\Exceptions\NonExistentBrickTypeException;
use Okipa\LaravelBrickable\Models\Brick;

trait HasBrickables
{
    /**
     * Associate an array of brick to the model.
     *
     * @param array $bricks
     *
     * @return \Illuminate\Support\Collection
     * @throws \Okipa\LaravelBrickable\Exceptions\NonExistentBrickTypeException
     */
    public function addBricks(array $bricks): Collection
    {
        $createdBricks = new Collection();
        foreach ($bricks as $brick) {
            $createdBricks->push($this->addBrick($brick[0], $brick[1]));
        }

        return $createdBricks;
    }

    /**
     * Associate a brick to the model.
     *
     * @param string $brickType
     * @param array $data
     *
     * @return \Okipa\LaravelBrickable\Models\Brick
     * @throws \Okipa\LaravelBrickable\Exceptions\NonExistentBrickTypeException
     */
    public function addBrick(string $brickType, array $data): Brick
    {
        $this->checkBrickTypeDoesExist($brickType);

        return $this->bricks()->create(['brick_type' => $brickType, 'data' => $data]);
    }

    /**
     * @param string $brickType
     *
     * @throws \Okipa\LaravelBrickable\Exceptions\NonExistentBrickTypeException
     */
    protected function checkBrickTypeDoesExist(string $brickType): void
    {
        if (! config('brickable.types.' . $brickType)) {
            throw new NonExistentBrickTypeException('The « ' . $brickType
                . ' » brick type configuration does not exist.');
        }
    }

    /**
     * @return mixed
     */
    public function bricks()
    {
        return $this->morphMany(app(config('brickable.model')), 'model');
    }

    /**
     * Get first brick from given brick type.
     *
     * @param string $brickType
     *
     * @return \Okipa\LaravelBrickable\Models\Brick|null
     * @throws \Okipa\LaravelBrickable\Exceptions\NonExistentBrickTypeException
     */
    public function getFirstBrick(string $brickType): ?Brick
    {
        $this->checkBrickTypeDoesExist($brickType);

        return $this->getBricks()->where('brick_type', $brickType)->first();
    }

    /**
     * Get the model associated bricks.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getBricks(): Collection
    {
        return $this->bricks()->ordered()->get();
    }

    /**
     * Display the model associated bricks HTML.
     *
     * @return string
     */
    public function displayBricks(): string
    {
        $html = '';
        $bricks = $this->getBricks();
        foreach ($bricks as $brick) {
            $html .= $brick->toHtml();
        }

        return $html;
    }
}
