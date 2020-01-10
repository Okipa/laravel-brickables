<?php

return [

    /*
     * The fully qualified class name of the brick model.
     */
    'model' => Okipa\LaravelBrickable\Models\Brick::class,

    /*
     * The available brick types declaration.
     */
    'types' => [
        'oneTextColumn' => [
            'label' => 'One text column',
            'view' => 'laravel-brickable::brickables.one-text-column',
        ],
        'twoTextColumns' => [
            'label' => 'Two text columns',
            'view' => 'laravel-brickable::brickables.two-text-columns',
        ],
        // add your content brick type configurations here ...
    ],
];