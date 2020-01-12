<?php

return [

    /*
     * The fully qualified class name of the brick model.
     */
    'brickModel' => Okipa\LaravelBrickables\Models\Brick::class,

    /*
     * Register here the available brickables.
     * Brickables will not be available for use if they are not declared here.
     */
    'registered' => [
        Okipa\LaravelBrickables\Brickables\OneTextColumn::class,
        Okipa\LaravelBrickables\Brickables\TwoTextColumns::class,
        // add your content brick type configurations here ...
    ],

];
