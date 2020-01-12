<?php

namespace Okipa\LaravelBrickables\Controllers;

use Illuminate\Http\Request;

class BricksController
{
    /**
     * @param Request $request
     */
    public function create(Request $request)
    {
        dd($request->all());
    }
}
