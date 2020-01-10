<?php

namespace Okipa\LaravelBrickables\Brickables;

use Okipa\LaravelBrickables\Abstracts\Brickable;

class TwoTextColumns extends Brickable
{
    /**
     * @inheritDoc
     */
    public function setLabel(): string
    {
        return __('Two text columns');
    }

    /**
     * @inheritDoc
     */
    public function setViewPath(): string
    {
        return 'laravel-brickables::brickables.two-text-columns';
    }

    /**
     * @inheritDoc
     */
    public function setRoutes(): array
    {
        return [
            'edit' => 'brickable.edit',
            'destroy' => 'brickable.destroy',
        ];
    }
}
