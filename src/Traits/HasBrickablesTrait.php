<?php

namespace Okipa\LaravelBrickables\Traits;

use Illuminate\Support\Collection;
use Okipa\LaravelBrickables\Abstracts\Brickable;
use Okipa\LaravelBrickables\Exceptions\InvalidBrickableClassException;
use Okipa\LaravelBrickables\Exceptions\NotRegisteredBrickableClassException;
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
    public function addBrick(string $brickableClass, array $data): Brick
    {
        $this->verifyBrickableType($brickableClass);
        $this->verifyBrickableRegistration($brickableClass);

        return $this->bricks()->create(['brickable_type' => $brickableClass, 'data' => $data]);
    }

    /**
     * @param string $brickableClass
     *
     * @throws \Okipa\LaravelBrickables\Exceptions\InvalidBrickableClassException
     */
    protected function verifyBrickableType(string $brickableClass): void
    {
        if (! app($brickableClass) instanceof Brickable) {
            throw new InvalidBrickableClassException('The given ' . $brickableClass
                . ' brickable class should extend ' . Brickable::class . '.');
        }
    }

    /**
     * @param string $brickableClass
     *
     * @throws \Okipa\LaravelBrickables\Exceptions\NotRegisteredBrickableClassException
     */
    protected function verifyBrickableRegistration(string $brickableClass): void
    {
        if (! in_array($brickableClass, config('brickables.registered'))) {
            throw new NotRegisteredBrickableClassException('The given ' . $brickableClass
                . ' brickable is not registered in the config(\'brickables.registered\') array.');
        }
    }

    /**
     * @return mixed
     */
    public function bricks()
    {
        return $this->morphMany(app(config('brickables.brickModel')), 'model');
    }

    /**
     * @inheritDoc
     */
    public function getFirstBrick(string $brickableClass): ?Brick
    {
        $this->verifyBrickableType($brickableClass);
        $this->verifyBrickableRegistration($brickableClass);

        return $this->getBricks()->where('brickable_type', $brickableClass)->first();
    }

    /**
     * @inheritDoc
     */
    public function getBricks(): Collection
    {
        return $this->bricks()->ordered()->get();
    }
}
