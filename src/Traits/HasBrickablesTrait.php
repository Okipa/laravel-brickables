<?php

namespace Okipa\LaravelBrickables\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Okipa\LaravelBrickables\Abstracts\Brickable;
use Okipa\LaravelBrickables\Exceptions\BrickableCannotBeHandledException;
use Okipa\LaravelBrickables\Exceptions\InvalidBrickableClassException;
use Okipa\LaravelBrickables\Exceptions\NotRegisteredBrickableClassException;
use Okipa\LaravelBrickables\Facades\Brickables;
use Okipa\LaravelBrickables\Models\Brick;

trait HasBrickablesTrait
{
    /**
     * @param array $bricksData
     *
     * @return \Illuminate\Support\Collection
     * @throws \Okipa\LaravelBrickables\Exceptions\BrickableCannotBeHandledException
     * @throws \Okipa\LaravelBrickables\Exceptions\InvalidBrickableClassException
     * @throws \Okipa\LaravelBrickables\Exceptions\NotRegisteredBrickableClassException
     */
    public function addBricks(array $bricksData): Collection
    {
        $createdBricks = new Collection();
        foreach ($bricksData as $brickData) {
            $brickableClass = $brickData[0];
            $data = data_get($brickData, 1, []);
            $brick = $this->addBrick($brickableClass, $data);
            $createdBricks->push($brick);
        }

        return $createdBricks;
    }

    /**
     * @param string $brickableClass
     * @param array $data
     *
     * @return \Okipa\LaravelBrickables\Models\Brick
     * @throws \Okipa\LaravelBrickables\Exceptions\BrickableCannotBeHandledException
     * @throws \Okipa\LaravelBrickables\Exceptions\InvalidBrickableClassException
     * @throws \Okipa\LaravelBrickables\Exceptions\NotRegisteredBrickableClassException
     */
    public function addBrick(string $brickableClass, array $data = []): Brick
    {
        $this->checkBrickableTypeIsInstanceOfBrickable($brickableClass);
        $this->checkBrickableIsRegistered($brickableClass);
        $this->checkBrickableCanBeHandled($brickableClass);
        $brick = $this->createBrick($brickableClass, $data);
        $this->handleMaxNumberOfBricks($brickableClass);

        return $brick;
    }

    /**
     * @param string $brickableClass
     *
     * @throws \Okipa\LaravelBrickables\Exceptions\InvalidBrickableClassException
     */
    public function checkBrickableTypeIsInstanceOfBrickable(string $brickableClass): void
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
    protected function checkBrickableIsRegistered(string $brickableClass): void
    {
        if (! in_array($brickableClass, config('brickables.registered'))) {
            throw new NotRegisteredBrickableClassException('The given ' . $brickableClass
                . ' brickable is not registered in the config(\'brickables.registered\') array.');
        }
    }

    /**
     * @param string $brickableClass
     *
     * @throws \Okipa\LaravelBrickables\Exceptions\BrickableCannotBeHandledException
     */
    public function checkBrickableCanBeHandled(string $brickableClass): void
    {
        if (! $this->isAllowedToHandle($brickableClass)) {
            throw new BrickableCannotBeHandledException('The given ' . $brickableClass
                . ' brickable cannot be handled by the ' . $this->getMorphClass() . ' Eloquent model.');
        }
    }

    public function isAllowedToHandle(string $brickableClass): bool
    {
        $authorizedBrickables = data_get($this, 'brickables.canOnlyHandle');
        if (! $authorizedBrickables) {
            return true;
        }

        return in_array($brickableClass, $authorizedBrickables);
    }

    /**
     * @param string $brickableClass
     * @param array $data
     *
     * @return \Okipa\LaravelBrickables\Models\Brick
     */
    protected function createBrick(string $brickableClass, array $data): Brick
    {
        /** @var Brickable $brickable */
        $brickable = app($brickableClass);
        $brickModel = $brickable->getBrickModel();
        $brickModel->model_type = $this->getMorphClass();
        $brickModel->model_id = $this->id;
        $brickModel->brickable_type = $brickableClass;
        $brickModel->data = $data;
        $brickModel->save();

        return $brickModel;
    }

    /**
     * @param string $brickableClass
     *
     * @return void
     */
    protected function handleMaxNumberOfBricks(string $brickableClass): void
    {
        $maxNumberOfBricks = $this->getMaxNumberOfBricksFor($brickableClass);
        if ($maxNumberOfBricks) {
            $except = $this->getBricks($brickableClass)->reverse()->take($maxNumberOfBricks);
            $this->clearBricksExcept($brickableClass, $except);
        }
    }

    /**
     * @param string $brickableClass
     *
     * @return int
     */
    protected function getMaxNumberOfBricksFor(string $brickableClass): int
    {
        return (int) data_get($this, 'brickables.numberOfBricks.' . $brickableClass . '.max', 0);
    }

    public function getBricks(?string $brickableClass = null): Collection
    {
        /** @var \Okipa\LaravelBrickables\Models\Brick $bricksBaseModel */
        $bricksBaseModel = app(config('brickables.bricks.model'));
        $query = $bricksBaseModel->where('model_type', $this->getMorphClass())->where('model_id', $this->id);
        if ($brickableClass) {
            $query->where('brickable_type', $brickableClass);
        }
        $bricks = $query->ordered()->get();

        return Brickables::castBricks($bricks);
    }

    public function clearBricksExcept(string $brickableClass, Collection $excludeBricks): void
    {
        $this->getBricks($brickableClass)->reject(function (Brick $brick) use ($excludeBricks) {
            return $excludeBricks->where($brick->getKeyName(), $brick->getKey())->count();
        })->each->delete();
    }

    public function clearBricks(?string $brickableClass = null): void
    {
        $this->getBricks($brickableClass)->each->delete();
    }

    public function getFirstBrick(?string $brickableClass = null): ?Brick
    {
        return $this->getBricks($brickableClass)->first();
    }

    public function getReadableClassName(): string
    {
        return __(ucfirst(Str::snake(class_basename($this), ' ')));
    }

    public function canAddBricksFrom(string $brickableClass): bool
    {
        $maxNumberOfBricks = $this->getMaxNumberOfBricksFor($brickableClass);
        if (! $maxNumberOfBricks) {
            return true;
        }

        return $this->getBricks($brickableClass)->count() < $maxNumberOfBricks;
    }

    public function canDeleteBricksFrom(string $brickableClass): bool
    {
        $minNumberOfBricks = $this->getMinNumberOfBricksFor($brickableClass);
        if (! $minNumberOfBricks) {
            return true;
        }

        return $this->getBricks($brickableClass)->count() > $minNumberOfBricks;
    }

    protected function getMinNumberOfBricksFor(string $brickableClass): int
    {
        return (int) data_get($this, 'brickables.numberOfBricks.' . $brickableClass . '.min', 0);
    }
}
