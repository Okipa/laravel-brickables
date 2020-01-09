<?php

namespace Okipa\LaravelBrickable\Abstracts;

abstract class BrickableAbstract
{
    /** @property string $viewPath */
    protected $viewPath;

    /**
     * BrickAbstract constructor.
     */
    public function __construct()
    {
        $this->viewPath = $this->setViewPath();
    }

    /**
     * Get the brick view path used for HTML rendering.
     *
     * @return string
     */
    public function getViewPath(): string
    {
        return $this->viewPath;
    }

    /**
     * Set the brick view path used for HTML rendering.
     *
     * @return string
     */
    abstract protected function setViewPath(): string;
}
