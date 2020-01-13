<?php

namespace Okipa\LaravelBrickables\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Okipa\LaravelBrickables\Models\Brick;

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
        $adminPanelUrl = $request->admin_panel_url;

        return view($brickable->getFormViewPath(), [compact('brick', 'model', 'brickable', 'adminPanelUrl')]);
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
        $model->addBrick(get_class($brickable), $request->only(array_keys($brickable->getValidationRules())));

        return redirect()->to($request->admin_panel_url)->with(
            'success',
            __($brickable->getLabel() . ' brick has been stored for ' . Str::snake(class_basename($model), ' ') . '.')
        );
    }

    /**
     * @param \Okipa\LaravelBrickables\Models\Brick $brick
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Brick $brick, Request $request)
    {
        /** @var \Okipa\LaravelBrickables\Contracts\HasBrickables $model */
        $model = $brick->model;
        /** @var \Okipa\LaravelBrickables\Abstracts\Brickable $brickable */
        $brickable = (new $brick->brickable_type);
        $adminPanelUrl = $request->admin_panel_url;

        return view($brickable->getFormViewPath(), compact('brick', 'model', 'brickable', 'adminPanelUrl'));
    }

    /**
     * @param \Okipa\LaravelBrickables\Models\Brick $brick
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Brick $brick, Request $request)
    {
        /** @var \Okipa\LaravelBrickables\Contracts\HasBrickables $model */
        $model = $brick->model;
        /** @var \Okipa\LaravelBrickables\Abstracts\Brickable $brickable */
        $brickable = (new $brick->brickable_type);
        $brick->data = $request->only(array_keys($brickable->getValidationRules()));
        $brick->save();

        return redirect()->to($request->admin_panel_url)->with(
            'success',
            __($brickable->getLabel() . ' brick has been updated for ' . Str::snake(class_basename($model), ' ') . '.')
        );
    }

    /**
     * @param \Okipa\LaravelBrickables\Models\Brick $brick
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Brick $brick, Request $request)
    {
        /** @var \Okipa\LaravelBrickables\Contracts\HasBrickables $model */
        $model = $brick->model;
        /** @var \Okipa\LaravelBrickables\Abstracts\Brickable $brickable */
        $brickable = (new $brick->brickable_type);
        $brick->delete();

        return redirect()->to($request->admin_panel_url)->with(
            'success',
            __($brickable->getLabel() . ' brick has been deleted for ' . Str::snake(class_basename($model), ' ') . '.')
        );
    }
}
