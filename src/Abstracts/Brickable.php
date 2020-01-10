<?php

namespace Okipa\LaravelBrickables\Abstracts;

abstract class Brickable
{
    /** @property string $label */
    protected $label;

    /** @property string $viewPath */
    protected $viewPath;

    /** @property string $editRoutes */
    protected $editRouteName;

    /** @property string $editRoutes */
    protected $destroyRouteName;

    /**
     * Brickable constructor.
     */
    public function __construct()
    {
        $this->label = $this->setLabel();
        $this->viewPath = $this->setViewPath();
        $this->editRouteName = $this->setEditRouteName();
        $this->destroyRouteName = $this->setDestroyRouteName();
    }

    /**
     * Set the brickable edit route name.
     *
     * @return string
     */
    public function setEditRouteName(): string
    {
        return 'brickable.edit';
    }

    /**
     * Set the brickable destroy route name.
     *
     * @return string
     */
    public function setDestroyRouteName(): string
    {
        return 'brickable.destroy';
    }

    /**
     * Get the brickable view path.
     *
     * @return string
     */
    public function getViewPath(): string
    {
        return $this->viewPath;
    }

    /**
     * Set the brickable view path.
     *
     * @return string
     */
    abstract public function setViewPath(): string;

    /**
     * Get the brickable label.
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Set the brickable label.
     *
     * @return string
     */
    abstract public function setLabel(): string;

    /**
     * Get the brickable action route.
     *
     * @param string $action
     *
     * @return string
     */
    public function route(string $action): string
    {
        return route($this->routes[$action]);
    }
}
