<?php

namespace Okipa\LaravelBrickables;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Okipa\LaravelBrickables\Contracts\HasBrickables;

class Brickables implements Htmlable
{
    /** @property string $html */
    protected $html;

    /**
     * Get the available brickables.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll(): Collection
    {
        $brickables = new Collection;
        foreach (config('brickables.registered') as $brickableClass) {
            $brickables->push(app($brickableClass));
        }

        return $brickables;
    }

    /**
     * Display all the model-related content bricks html at once.
     *
     * @param \Okipa\LaravelBrickables\Contracts\HasBrickables $model
     *
     * @return $this
     */
    public function bricks(HasBrickables $model): self
    {
        $this->html = view('laravel-brickables::bricks', ['model' => $model]);

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
        $this->html = view('laravel-brickables::admin.panel', ['model' => $model]);

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
