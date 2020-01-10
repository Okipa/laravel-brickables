<?php

namespace Okipa\LaravelBrickable;

use Illuminate\Contracts\Support\Htmlable;
use Okipa\LaravelBrickable\Contracts\HasBrickables;

class Brickables implements Htmlable
{
    protected string $html;

    /**
     * Get available brick types.
     *
     * @return array
     */
    public function getTypes(): array
    {
        return config('brickables.types');
    }

    /**
     * Get available brick types.
     *
     * @param string $brickType
     *
     * @return array
     */
    public function getType(string $brickType): array
    {
        return config('brickables.types.' . $brickType);
    }

    /**
     * Display all the model-related content bricks html at once.
     *
     * @param \Okipa\LaravelBrickable\Contracts\HasBrickables $model
     *
     * @return $this
     */
    public function display(HasBrickables $model): self
    {
        $this->html = view('laravel-brickable::bricks', ['model' => $model])->toHtml();

        return $this;
    }

    /**
     * Display the model-related content bricks admin panel html.
     *
     * @param \Okipa\LaravelBrickable\Contracts\HasBrickables $model
     *
     * @return $this
     */
    public function adminPanel(HasBrickables $model): self
    {
        $this->html = view('laravel-brickable::admin-panel', ['model' => $model]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toHtml()
    {
        return (string) $this->html;
    }
}
