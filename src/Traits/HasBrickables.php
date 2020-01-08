<?php

namespace Okipa\LaravelBrickable\Traits;

use Illuminate\Support\Collection;
use Okipa\LaravelBrickable\Contracts\Brickable;
use Okipa\LaravelBrickable\Models\Brick;

trait HasBrickables
{
    /**
     * @param string $brickType
     * @param array $data
     *
     * @return \Okipa\LaravelBrickable\Contracts\Brickable
     */
    public function addBrick(string $brickType, array $data): Brickable
    {
        $brick = $this->bricks()->create(['brick_type' => $brickType, 'data' => $data]);

        return $this->brickInstance($brick);
    }

    /**
     * @return mixed
     */
    public function bricks()
    {
        return $this->morphMany(Brick::class, 'model');
    }

    /**
     * @param \Okipa\LaravelBrickable\Models\Brick $brick
     *
     * @return \Okipa\LaravelBrickable\Contracts\Brickable
     */
    protected function brickInstance(Brick $brick): Brickable
    {
        $instance = app($brick->brick_type);
        $instance->id = $brick->id;
        $instance->fill($brick->data);

        return $instance;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getBricks(): Collection
    {
        $bricks = new Collection();
        foreach ($this->bricks as $brick) {
            $instance = $this->brickInstance($brick);
            $bricks->push($instance);
        }

        return $bricks;
    }
}
