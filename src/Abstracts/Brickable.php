<?php

namespace Okipa\LaravelBrickables\Abstracts;

abstract class Brickable
{
    /** @property string $label */
    protected $label;

    /** @property string $viewPath */
    protected $viewPath;

    /** @property array $routes */
    protected $routes;

    /**
     * Brickable constructor.
     */
    public function __construct()
    {
        $this->label = $this->setLabel();
        $this->viewPath = $this->setViewPath();
        $this->routes = $this->setRoutes();
    }

    /**
     * Set the brickable routes.
     *
     * @return array
     */
    abstract public function setRoutes(): array;

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
