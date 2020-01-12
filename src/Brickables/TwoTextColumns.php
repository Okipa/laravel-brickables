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
    public function setAdminViewPath(): string
    {
        return 'laravel-brickables::brickables.two-text-columns.admin';
    }

    /**
     * @inheritDoc
     */
    public function setTemplateViewPath(): string
    {
        return 'laravel-brickables::brickables.two-text-columns.template';
    }
}
