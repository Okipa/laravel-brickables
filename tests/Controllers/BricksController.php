<?php

namespace Okipa\LaravelBrickables\Tests\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class BricksController extends \Okipa\LaravelBrickables\Controllers\BricksController
{
    public function create(Request $request): View
    {
        $brick = null;
        /** @var \Okipa\LaravelBrickables\Abstracts\Brickable $brickable */
        $brickable = (new $request->brickable_type);

        return view($brickable->getFormViewPath(), compact('brick'));
    }
}
