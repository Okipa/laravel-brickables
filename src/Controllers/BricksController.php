<?php

namespace Okipa\LaravelBrickables\Controllers;

use Illuminate\Http\Request;
use Okipa\LaravelBrickables\Abstracts\Brickable;

class BricksController
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $brick = null;
        /** @var Brickable $brickable */
        $brickable = app($request->brickable);

        return view($brickable->getFormViewPath(), compact('brick', 'brickable'));
    }
}
