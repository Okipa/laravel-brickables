<?php

namespace Okipa\LaravelBrickable\Traits;

use Illuminate\Support\Collection;
use Okipa\LaravelBrickable\Abstracts\BrickableAbstract;
use Okipa\LaravelBrickable\Exceptions\InvalidBrickTypeException;
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
     * @throws \Okipa\LaravelBrickable\Exceptions\InvalidBrickTypeException
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
     * @throws \Okipa\LaravelBrickable\Exceptions\InvalidBrickTypeException
     */
    public function addBrick(string $brickType, array $data): Brick
    {
        $this->checkBrickTypeDoesExist($brickType);
        $this->checkBrickTypeIsCorrect($brickType);

        return $this->bricks()->create(['brick_type' => $brickType, 'data' => $data]);
    }

    /**
     * @param string $brickType
     *
     * @throws \Okipa\LaravelBrickable\Exceptions\NonExistentBrickTypeException
     */
    protected function checkBrickTypeDoesExist(string $brickType): void
    {
        if (! class_exists($brickType)) {
            throw new NonExistentBrickTypeException($brickType . ' class does not exist.');
        }
    }

    /**
     * @param string $brickType
     *
     * @throws \Okipa\LaravelBrickable\Exceptions\InvalidBrickTypeException
     */
    protected function checkBrickTypeIsCorrect(string $brickType): void
    {
        if (! app($brickType) instanceof BrickableAbstract) {
            throw new InvalidBrickTypeException('Brick type should extends ' . BrickableAbstract::class);
        }
    }

    /**
     * @return mixed
     */
    public function bricks()
    {
        return $this->morphMany(Brick::class, 'model');
    }

    /**
     * Get first brick from given brick type.
     *
     * @param string $brickType
     *
     * @return \Okipa\LaravelBrickable\Models\Brick|null
     * @throws \Okipa\LaravelBrickable\Exceptions\InvalidBrickTypeException
     * @throws \Okipa\LaravelBrickable\Exceptions\NonExistentBrickTypeException
     */
    public function getFirstBrick(string $brickType): ?Brick
    {
        $this->checkBrickTypeDoesExist($brickType);
        $this->checkBrickTypeIsCorrect($brickType);

        return $this->getBricks()->where('brick_type', $brickType)->first();
    }

    /**
     * Get the model associated bricks.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getBricks(): Collection
    {
        return $this->bricks;
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
