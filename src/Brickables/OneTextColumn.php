<?php

namespace Okipa\LaravelBrickables\Brickables;

use Okipa\LaravelBrickables\Abstracts\Brickable;

class OneTextColumn extends Brickable
{
    /**
     * @inheritDoc
     */
    public function setLabel(): string
    {
        return __('One text column');
    }

    /**
     * @inheritDoc
     */
    public function setTemplateViewPath(): string
    {
        return 'laravel-brickables::brickables.one-text-column.template';
    }

    /**
     * @inheritDoc
     */
    public function setAdminViewPath(): string
    {
        return 'laravel-brickables::brickables.one-text-column.admin';
    }
}
