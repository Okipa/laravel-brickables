<?php

namespace Okipa\LaravelBrickables\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
        $this->checkBrickableType($brickableClass);
        $this->checkBrickableIsRegistered($brickableClass);

        return $this->createBrick($brickableClass, $data);
    }

    /**
     * @param string $brickableClass
     *
     * @throws \Okipa\LaravelBrickables\Exceptions\InvalidBrickableClassException
     */
    protected function checkBrickableType(string $brickableClass): void
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
     * @inheritDoc
     */
    public function getFirstBrick(string $brickableClass): ?Brick
    {
        $this->checkBrickableType($brickableClass);
        $this->checkBrickableIsRegistered($brickableClass);

        return $this->getBricks()->where('brickable_type', $brickableClass)->first();
    }

    /**
     * @inheritDoc
     */
    public function getBricks(): Collection
    {
        /** @var \Okipa\LaravelBrickables\Models\Brick $bricksBaseModel */
        $bricksBaseModel = app(config('brickables.bricks.model'));
        $bricks = DB::table($bricksBaseModel->getTable())
            ->where('model_type', $this->getMorphClass())
            ->where('model_id', $this->id)
            ->get();

        return $this->castToBrickableRelatedModels($bricks);
    }

    /**
     * @param \Illuminate\Support\Collection $bricks
     *
     * @return \Illuminate\Support\Collection
     */
    protected function castToBrickableRelatedModels(Collection $bricks): Collection
    {
        $casted = new Collection;
        foreach ($bricks->pluck('brickable_type') as $brickableClass) {
            $brickableTypedBricks = $bricks->where('brickable_type', $brickableClass);
            /** @var \Okipa\LaravelBrickables\Abstracts\Brickable $brickable */
            $brickable = app($brickableClass);
            /** @var \Okipa\LaravelBrickables\Models\Brick $model */
            $model = $brickable->getBrickModel();
            $castedBricks = $model->hydrate($brickableTypedBricks->toArray());
            $casted->push($castedBricks);
        }

        return $casted->flatten();
    }

    /**
     * Get the model name readable for a human.
     *
     * @return string
     */
    public function getReadableClassName(): string
    {
        return __(ucfirst(Str::snake(class_basename($this), ' ')));
    }
}
