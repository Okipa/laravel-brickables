<?php

namespace Okipa\LaravelBrickables;

use Illuminate\Contracts\Support\Htmlable;
use Okipa\LaravelBrickables\Contracts\HasBrickables;

class Brickables implements Htmlable
{
    /** @property string $html */
    protected $html;

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
     * @param \Okipa\LaravelBrickables\Contracts\HasBrickables $model
     *
     * @return $this
     */
    public function display(HasBrickables $model): self
    {
        $this->html = view('laravel-brickables::bricks', ['model' => $model])->toHtml();

        return $this;
    }

    /**
     * Display the model-related content bricks admin panel html.
     *
     * @param \Okipa\LaravelBrickables\Contracts\HasBrickables $model
     *
     * @return $this
     */
    public function adminPanel(HasBrickables $model): self
    {
        $this->html = view('laravel-brickables::admin-panel', ['model' => $model]);

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
