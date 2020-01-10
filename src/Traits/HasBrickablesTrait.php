<?php

namespace Okipa\LaravelBrickables\Traits;

use Illuminate\Support\Collection;
use Okipa\LaravelBrickables\Exceptions\NonExistentBrickTypeException;
use Okipa\LaravelBrickables\Models\Brick;

trait HasBrickablesTrait
{
    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function addBrick(string $brickType, array $data): Brick
    {
        $this->checkBrickTypeDoesExist($brickType);

        return $this->bricks()->create(['brickable_type' => $brickType, 'data' => $data]);
    }

    /**
     * @param string $brickType
     *
     * @throws \Okipa\LaravelBrickables\Exceptions\NonExistentBrickTypeException
     */
    protected function checkBrickTypeDoesExist(string $brickType): void
    {
        if (! config('brickables.types.' . $brickType)) {
            throw new NonExistentBrickTypeException('The « ' . $brickType
                . ' » brick type configuration does not exist.');
        }
    }

    /**
     * @return mixed
     */
    public function bricks()
    {
        return $this->morphMany(app(config('brickables.model')), 'model');
    }

    /**
     * @inheritDoc
     */
    public function getFirstBrick(string $brickType): ?Brick
    {
        $this->checkBrickTypeDoesExist($brickType);

        return $this->getBricks()->where('brickable_type', $brickType)->first();
    }

    /**
     * @inheritDoc
     */
    public function getBricks(): Collection
    {
        return $this->bricks()->ordered()->get();
    }
}
