<?php

namespace Okipa\LaravelBrickable;

use Okipa\LaravelBrickable\Contracts\HasBrickables;

class Brickables
{
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
     * @return string
     */
    public function display(HasBrickables $model): string
    {
        return view('laravel-brickable::display.bricks', ['model' => $model]);
    }

    /**
     * Display the model-related content bricks admin panel html.
     *
     * @param \Okipa\LaravelBrickable\Contracts\HasBrickables $model
     *
     * @return string
     */
    public function adminPanel(HasBrickables $model): string
    {
        return view('laravel-brickable::display.admin-panel', ['model' => $model]);
    }
}
