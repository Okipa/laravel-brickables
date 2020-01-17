<?php

namespace Okipa\LaravelBrickables\Tests\Controllers;

use Illuminate\Http\Request;

class BricksController extends \Okipa\LaravelBrickables\Controllers\BricksController
{
    /** @inheritDoc */
    public function create(Request $request)
    {
        /** @var \Okipa\LaravelBrickables\Abstracts\Brickable $brickable */
        $brickable = (new $request->brickable_type);

        return view($brickable->getFormViewPath(), ['data' => 'dummy']);
    }
}
