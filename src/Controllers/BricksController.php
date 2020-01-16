<?php

namespace Okipa\LaravelBrickables\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
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
        $brick = $model->addBrick($request->brickable_type, $request->only($brickable->getValidatedKeys()));

        return $this->sendBrickCreatedResponse($request, $brick);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Okipa\LaravelBrickables\Models\Brick $brick
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendBrickCreatedResponse(Request $request, Brick $brick)
    {
        return redirect()->to($request->admin_panel_url)
            ->with('success', __('The entry :model > :brickable has been created.', [
                'brickable' => $brick->brickable->getLabel(),
                'model' => $brick->model->getReadableClassName(),
            ]));
    }

    /**
     * @param \Okipa\LaravelBrickables\Models\Brick $brick
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Brick $brick, Request $request)
    {
        $brick = $this->castToRelatedBrickModel($brick);
        /** @var \Okipa\LaravelBrickables\Contracts\HasBrickables $model */
        $model = $brick->model;
        /** @var \Okipa\LaravelBrickables\Abstracts\Brickable $brickable */
        $brickable = $brick->brickable;
        $adminPanelUrl = $request->admin_panel_url;

        return view($brickable->getFormViewPath(), compact('brick', 'model', 'brickable', 'adminPanelUrl'));
    }

    /**
     * Cast given brick to its brickable brick model.
     *
     * @param \Okipa\LaravelBrickables\Models\Brick $brick
     *
     * @return Brick
     */
    public function castToRelatedBrickModel(Brick $brick): Brick
    {
        /** @var \Okipa\LaravelBrickables\Models\Brick $model */
        $model = $brick->brickable->getBrickModel();

        return $model->findOrFail($brick->id);
    }

    /**
     * @param \Okipa\LaravelBrickables\Models\Brick $brick
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Brick $brick, Request $request)
    {
        $brick = $this->castToRelatedBrickModel($brick);
        $request->validate($brick->brickable->getValidationRules());
        $brick->data = $request->only($brick->brickable->getValidatedKeys());
        $brick->save();

        return $this->sendBrickUpdatedResponse($request, $brick);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Okipa\LaravelBrickables\Models\Brick $brick
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendBrickUpdatedResponse(Request $request, Brick $brick)
    {
        return redirect()->to($request->admin_panel_url)
            ->with('success', __('The entry :model > :brickable has been updated.', [
                'brickable' => $brick->brickable->getLabel(),
                'model' => $brick->model->getReadableClassName(),
            ]));
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
        $brick = $this->castToRelatedBrickModel($brick);
        $brickClone = clone $brick;
        $brick->delete();

        return $this->sendBrickDestroyedResponse($request, $brickClone);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Okipa\LaravelBrickables\Models\Brick $brick
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendBrickDestroyedResponse(Request $request, Brick $brick)
    {
        return redirect()->to($request->admin_panel_url)
            ->with('success', __('The entry :model > :brickable has been deleted.', [
                'brickable' => $brick->brickable->getLabel(),
                'model' => $brick->model->getReadableClassName(),
            ]));
    }

    /**
     * @param \Okipa\LaravelBrickables\Models\Brick $brick
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function moveUp(Brick $brick, Request $request)
    {
        $brick = $this->castToRelatedBrickModel($brick);
        $brick->moveOrderUp();

        return $this->sendBrickMovedResponse($request, $brick->fresh());
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Okipa\LaravelBrickables\Models\Brick $brick
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendBrickMovedResponse(Request $request, Brick $brick)
    {
        return redirect()->to($request->admin_panel_url);
    }

    /**
     * @param \Okipa\LaravelBrickables\Models\Brick $brick
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function moveDown(Brick $brick, Request $request)
    {
        $brick = $this->castToRelatedBrickModel($brick);
        $brick->moveOrderDown();

        return $this->sendBrickMovedResponse($request, $brick->fresh());
    }
}
