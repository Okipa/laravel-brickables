<?php

namespace Okipa\LaravelBrickables\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        /** @var \Okipa\LaravelBrickables\Contracts\HasBrickables $model */
        $model = (new $request->model_type)->findOrFail($request->model_id);
        /** @var \Okipa\LaravelBrickables\Abstracts\Brickable $brickable */
        $brickable = (new $request->brickable_type);

        return view($brickable->getFormViewPath(), compact('brick', 'model', 'brickable'));
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Okipa\LaravelBrickables\Exceptions\InvalidBrickableClassException
     * @throws \Okipa\LaravelBrickables\Exceptions\NotRegisteredBrickableClassException
     */
    public function store(Request $request)
    {
        /** @var \Okipa\LaravelBrickables\Contracts\HasBrickables $model */
        $model = (new $request->model_type)->findOrFail($request->model_id);
        /** @var \Okipa\LaravelBrickables\Abstracts\Brickable $brickable */
        $brickable = (new $request->brickable_type);
        $request->validate($brickable->getValidationRules());
        $brick = $model->addBrick(get_class($brickable), $request->all());

        return route('brick.edit', compact('brick', 'model', 'brickable'))->with(
            'success',
            __($brickable->getLabel() . ' brick has been stored for ' . Str::snake(class_basename($model), ' ') . '.')
        );
    }
}
