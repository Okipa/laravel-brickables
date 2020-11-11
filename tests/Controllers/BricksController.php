<?php

namespace Okipa\LaravelBrickables\Tests\Controllers;

use Illuminate\Http\Request;

class BricksController extends \Okipa\LaravelBrickables\Controllers\BricksController
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        $brick = null;
        /** @var \Okipa\LaravelBrickables\Abstracts\Brickable $brickable */
        $brickable = app($request->brickable_type);

        return view($brickable->getFormViewPath(), compact('brick'));
    }
}
