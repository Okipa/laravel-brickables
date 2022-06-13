<?php

namespace Okipa\LaravelBrickables\Abstracts;

use Illuminate\Support\Str;
use Okipa\LaravelBrickables\Controllers\BricksController;
use Okipa\LaravelBrickables\Models\Brick;

abstract class Brickable
{
    protected string $brickModelClass;

    protected string $bricksControllerClass;

    protected string $formViewPath;

    protected string $label;

    protected string $brickViewPath;

    protected string $storeRouteName;

    protected string $editRouteName;

    protected string $updateRouteName;

    protected string $destroyRouteName;

    protected string $moveUpRouteName;

    protected string $moveDownRouteName;

    protected string|null $cssResourcePath;

    protected string|null $jsResourcePath;

    public function __construct()
    {
        $this->brickModelClass = $this->setBrickModelClass();
        $this->bricksControllerClass = $this->setBricksControllerClass();
        $this->brickViewPath = $this->setBrickViewPath();
        $this->label = $this->setLabel();
        $this->formViewPath = $this->setFormViewPath();
        $this->storeRouteName = $this->setStoreRouteName();
        $this->editRouteName = $this->setEditRouteName();
        $this->updateRouteName = $this->setUpdateRouteName();
        $this->destroyRouteName = $this->setDestroyRouteName();
        $this->moveUpRouteName = $this->setMoveUpRouteName();
        $this->moveDownRouteName = $this->setMoveDownRouteName();
        $this->cssResourcePath = $this->setCssResourcePath();
        $this->jsResourcePath = $this->setJsResourcePath();
    }

    protected function setBrickModelClass(): string
    {
        return config('brickables.bricks.model');
    }

    protected function setBricksControllerClass(): string
    {
        return config('brickables.bricks.controller');
    }

    protected function setStoreRouteName(): string
    {
        return 'brick.store';
    }

    protected function setEditRouteName(): string
    {
        return 'brick.edit';
    }

    protected function setUpdateRouteName(): string
    {
        return 'brick.update';
    }

    protected function setDestroyRouteName(): string
    {
        return 'brick.destroy';
    }

    protected function setMoveUpRouteName(): string
    {
        return 'brick.move.up';
    }

    protected function setMoveDownRouteName(): string
    {
        return 'brick.move.down';
    }

    public function getFormViewPath(): string
    {
        return $this->formViewPath;
    }

    protected function setFormViewPath(): string
    {
        return 'laravel-brickables::brickables.' . Str::snake(class_basename($this), '-') . '.form';
    }

    public function getBrickViewPath(): string
    {
        return $this->brickViewPath;
    }

    protected function setBrickViewPath(): string
    {
        return 'laravel-brickables::brickables.' . Str::snake(class_basename($this), '-') . '.brick';
    }

    public function getLabel(): string
    {
        return (string) __($this->label);
    }

    public function setLabel(): string
    {
        return ucfirst(Str::snake(class_basename($this), ' '));
    }

    public function getStoreRoute(mixed $parameters = []): string
    {
        return route($this->storeRouteName, $parameters);
    }

    public function getEditRoute(mixed $parameters = []): string
    {
        return route($this->editRouteName, $parameters);
    }

    public function getUpdateRoute(mixed $parameters = []): string
    {
        return route($this->updateRouteName, $parameters);
    }

    public function getDestroyRoute(mixed $parameters = []): string
    {
        return route($this->destroyRouteName, $parameters);
    }

    public function getMoveUpRoute(mixed $parameters = []): string
    {
        return route($this->moveUpRouteName, $parameters);
    }

    public function getMoveDownRoute(mixed $parameters = []): string
    {
        return route($this->moveDownRouteName, $parameters);
    }

    public function getBrickModel(): Brick
    {
        return app($this->brickModelClass);
    }

    public function getBricksController(): BricksController
    {
        return app($this->bricksControllerClass);
    }

    public function getCssResourcePath(): string|null
    {
        return $this->cssResourcePath;
    }

    protected function setCssResourcePath(): string|null
    {
        return null;
    }

    public function getJsResourcePath(): string|null
    {
        return $this->jsResourcePath;
    }

    protected function setJsResourcePath(): string|null
    {
        return null;
    }

    abstract public function validateStoreInputs(): array;

    abstract public function validateUpdateInputs(): array;
}
