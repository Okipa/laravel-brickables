<?php

namespace Okipa\LaravelBrickables\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Okipa\LaravelBrickables\Abstracts\Brickable;
use Okipa\LaravelBrickables\Contracts\HasBrickables;
use Okipa\LaravelBrickables\Models\Brick;

class BricksController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->only('brickable_type'), ['brickable_type' => ['required', 'string']]);
        if ($validator->fails()) {
            return redirect()->to($request->admin_panel_url)->withErrors($validator)->withInput();
        }
        $brick = null;
        /** @var \Okipa\LaravelBrickables\Contracts\HasBrickables $model */
        $model = (new $request->model_type)->findOrFail($request->model_id);
        /** @var \Okipa\LaravelBrickables\Abstracts\Brickable $brickable */
        $brickable = (new $request->brickable_type);
        $adminPanelUrl = $request->admin_panel_url;

        return view($brickable->getFormViewPath(), compact('brick', 'model', 'brickable', 'adminPanelUrl'));
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
        $model->addBrick($request->brickable_type, $request->only($brickable->getValidatedKeys()));

        return $this->sendBrickCreatedResponse($request, $model, $brickable);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Okipa\LaravelBrickables\Contracts\HasBrickables $model
     * @param \Okipa\LaravelBrickables\Abstracts\Brickable $brickable
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendBrickCreatedResponse(Request $request, HasBrickables $model, Brickable $brickable)
    {
        return redirect()->to($request->admin_panel_url)->with(
            'success',
            __($brickable->getLabel() . ' brick has been added on ' . $model->getReadableClassName() . '.')
        );
    }

    /**
     * @param \Okipa\LaravelBrickables\Models\Brick $brick
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Brick $brick)
    {
        /** @var \Okipa\LaravelBrickables\Contracts\HasBrickables $model */
        $model = $brick->model;
        /** @var \Okipa\LaravelBrickables\Abstracts\Brickable $brickable */
        $brickable = $brick->brickable;
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
        $request->validate($brick->brickable->getValidationRules());
        $brick->data = $request->only($brick->brickable->getValidatedKeys());
        $brick->save();

        return $this->sendBrickUpdatedResponse($request, $brick->model, $brick->brickable);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Okipa\LaravelBrickables\Contracts\HasBrickables $model
     * @param \Okipa\LaravelBrickables\Abstracts\Brickable $brickable
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendBrickUpdatedResponse(Request $request, HasBrickables $model, Brickable $brickable)
    {
        return redirect()->to($request->admin_panel_url)->with(
            'success',
            __($brickable->getLabel() . ' brick has been updated for ' . $model->getReadableClassName() . '.')
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
            __($brickable->getLabel() . ' brick has been deleted from ' . $model->getReadableClassName() . '.')
        );
    }
}
