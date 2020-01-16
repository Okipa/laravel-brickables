<?php

namespace Okipa\LaravelBrickables\Abstracts;

use Illuminate\Support\Str;
use Okipa\LaravelBrickables\Models\Brick;

abstract class Brickable
{
    /** @property string $brickModelClass */
    protected $brickModelClass;

    /** @property string $controllerClass */
    protected $controllerClass;

    /** @property string $label */
    protected $label;

    /** @property string $adminViewPath */
    protected $adminViewPath;

    /** @property string $templateViewPath */
    protected $templateViewPath;

    /** @property array $validationRules */
    protected $validationRules;

    /** @property string $storeRouteName */
    protected $storeRouteName;

    /** @property string $editRouteName */
    protected $editRouteName;

    /** @property string $updateRouteName */
    protected $updateRouteName;

    /** @property string $destroyRouteName */
    protected $destroyRouteName;

    /** @property string $moveUpRouteName */
    protected $moveUpRouteName;

    /** @property string $moveUpRouteName */
    protected $moveDownRouteName;

    /**
     * Brickable constructor.
     */
    public function __construct()
    {
        $this->brickModelClass = $this->setBrickModelClass();
        $this->controllerClass = $this->setControllerClass();
        $this->label = $this->setLabel();
        $this->templateViewPath = $this->setBrickViewPath();
        $this->adminViewPath = $this->setFormViewPath();
        $this->validationRules = $this->setValidationRules();
        $this->storeRouteName = $this->setStoreRouteName();
        $this->editRouteName = $this->setEditRouteName();
        $this->updateRouteName = $this->setUpdateRouteName();
        $this->destroyRouteName = $this->setDestroyRouteName();
        $this->moveUpRouteName = $this->setMoveUpRouteName();
        $this->moveDownRouteName = $this->setMoveDownRouteName();
    }

    /**
     * Set the brickable related brick model class.
     *
     * @return string
     */
    protected function setBrickModelClass(): string
    {
        return config('brickables.defaultBrickModel');
    }

    /**
     * Set the brickable related controller class.
     *
     * @return string
     */
    protected function setControllerClass(): string
    {
        return config('brickables.defaultBricksController');
    }

    /**
     * Set the brickable template view path.
     *
     * @return string
     */
    protected function setBrickViewPath(): string
    {
        return 'laravel-brickables::brickables.' . Str::snake(class_basename($this), '-') . '.brick';
    }

    /**
     * Set the brickable admin view path.
     *
     * @return string
     */
    protected function setFormViewPath(): string
    {
        return 'laravel-brickables::brickables.' . Str::snake(class_basename($this), '-') . '.form';
    }

    /**
     * Set the brickable store route name.
     *
     * @return string
     */
    protected function setStoreRouteName(): string
    {
        return 'brick.store';
    }

    /**
     * Set the brickable edit route name.
     *
     * @return string
     */
    protected function setEditRouteName(): string
    {
        return 'brick.edit';
    }

    /**
     * Set the brickable update route name.
     *
     * @return string
     */
    protected function setUpdateRouteName(): string
    {
        return 'brick.update';
    }

    /**
     * Set the brickable destroy route name.
     *
     * @return string
     */
    protected function setDestroyRouteName(): string
    {
        return 'brick.destroy';
    }

    /**
     * Set the brickable move up route name.
     *
     * @return string
     */
    protected function setMoveUpRouteName(): string
    {
        return 'brick.move.up';
    }

    /**
     * Set the brickable move down route name.
     *
     * @return string
     */
    protected function setMoveDownRouteName(): string
    {
        return 'brick.move.down';
    }

    /**
     * Get the brickable data keys which are defined in the validation rules.
     *
     * @return array
     */
    public function getValidatedKeys(): array
    {
        return array_keys($this->getValidationRules());
    }

    /**
     * Get the brickable validation rules.
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return $this->validationRules;
    }

    /**
     * Set the brickable validation rules.
     *
     * @return array
     */
    abstract protected function setValidationRules(): array;

    /**
     * Get the management view path.
     *
     * @return string
     */
    public function getFormViewPath(): string
    {
        return $this->adminViewPath;
    }

    /**
     * Get the template view path.
     *
     * @return string
     */
    public function getBrickViewPath(): string
    {
        return $this->templateViewPath;
    }

    /**
     * Get the brickable label.
     *
     * @return string
     */
    public function getLabel(): string
    {
        return __($this->label);
    }

    /**
     * Set the brickable label.
     *
     * @return string
     */
    public function setLabel(): string
    {
        return ucfirst(Str::snake(class_basename($this), ' '));
    }

    /**
     * Get the brickable store route.
     *
     * @param mixed $parameters
     *
     * @return string
     */
    public function getStoreRoute($parameters = []): string
    {
        return route($this->storeRouteName, $parameters);
    }

    /**
     * Get the brickable edit route.
     *
     * @param mixed $parameters
     *
     * @return string
     */
    public function getEditRoute($parameters = []): string
    {
        return route($this->editRouteName, $parameters);
    }

    /**
     * Get the brickable update route.
     *
     * @param mixed $parameters
     *
     * @return string
     */
    public function getUpdateRoute($parameters = []): string
    {
        return route($this->updateRouteName, $parameters);
    }

    /**
     * Get the brickable destroy route.
     *
     * @param mixed $parameters
     *
     * @return string
     */
    public function getDestroyRoute($parameters = []): string
    {
        return route($this->destroyRouteName, $parameters);
    }

    /**
     * Get the brickable move up route.
     *
     * @param mixed $parameters
     *
     * @return string
     */
    public function getMoveUpRoute($parameters = []): string
    {
        return route($this->moveUpRouteName, $parameters);
    }

    /**
     * Get the brickable move down route.
     *
     * @param mixed $parameters
     *
     * @return string
     */
    public function getMoveDownRoute($parameters = []): string
    {
        return route($this->moveDownRouteName, $parameters);
    }

    /**
     * Get the brickable related brick model.
     *
     * @return \Okipa\LaravelBrickables\Models\Brick
     */
    public function getBrickModel(): Brick
    {
        return app($this->brickModelClass);
    }
}
