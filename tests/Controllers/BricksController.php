<?php

namespace Okipa\LaravelBrickables\Tests\Controllers;

use Illuminate\Http\Request;

class BricksController extends \Okipa\LaravelBrickables\Controllers\BricksController
{
    /** @inheritDoc */
    public function create(Request $request)
    {
        $brick = null;
        /** @var \Okipa\LaravelBrickables\Abstracts\Brickable $brickable */
        $brickable = (new $request->brickable_type);

        return view($brickable->getFormViewPath(), compact('brick'));
    }
}
