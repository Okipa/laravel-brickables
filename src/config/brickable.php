<?php

return [

    /*
     * The fully qualified class name of the brick model.
     */
    'brick_model' => Okipa\LaravelBrickable\Models\BrickableAbstract::class,

    /*
     * The list of the available brickables
     */
    'brickables' => [

        'two_columns_text' => [
            'title' => 'Two column text',
            'controller' => \Okipa\LaravelBrickable\Controllers\TwoColumnsTextController::class,
            'model' => \Okipa\LaravelBrickable\Models\TwoColumnsText::class,
        ],

        // add you own brickables here, following the example above ...
    ],

];
