<?php

return [

    /*
     * The fully qualified class name of the brick model.
     */
    'model' => Okipa\LaravelBrickables\Models\Brick::class,

    /*
     * The available brick types declaration.
     */
    'types' => [
        'oneTextColumn' => [
            'label' => 'One text column',
            'view' => 'laravel-brickables::brickables.one-text-column',
        ],
        'twoTextColumns' => [
            'label' => 'Two text columns',
            'view' => 'laravel-brickables::brickables.two-text-columns',
        ],
        // add your content brick type configurations here ...
    ],
];
