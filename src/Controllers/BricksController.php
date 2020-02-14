<?php

namespace Okipa\LaravelBrickables\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Okipa\LaravelBrickables\Facades\Brickables;
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
     * @throws \Okipa\LaravelBrickables\Exceptions\BrickableCannotBeHandledException
     */
    public function store(Request $request)
    {
        /** @var \Okipa\LaravelBrickables\Contracts\HasBrickables $model */
        $model = (new $request->model_type)->findOrFail($request->model_id);
        /** @var \Okipa\LaravelBrickables\Abstracts\Brickable $brickable */
        $brickable = (new $request->brickable_type);
        $validated = $request->validate($brickable->getStoreValidationRules());
        $brick = $model->addBrick($request->brickable_type, $validated);
        $this->stored($request, $brick);

        return $this->sendBrickCreatedResponse($request, $brick);
    }

    /**
     * Execute additional treatment once the brick has been stored.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Okipa\LaravelBrickables\Models\Brick $brick
     *
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function stored(Request $request, Brick $brick): void
    {
        //
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
        $brick = Brickables::castBrick($brick);
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
        $brick = Brickables::castBrick($brick);
        $validated = $request->validate($brick->brickable->getUpdateValidationRules());
        $brick->data = $validated;
        $brick->save();
        $this->updated($request, $brick);

        return $this->sendBrickUpdatedResponse($request, $brick);
    }

    /**
     * Execute additional treatment once the brick has been updated.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Okipa\LaravelBrickables\Models\Brick $brick
     *
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function updated(Request $request, Brick $brick): void
    {
        //
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
        $brick = Brickables::castBrick($brick);
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
        $brick = Brickables::castBrick($brick);
        $brick->moveOrderUp();

        return $this->sendBrickMovedResponse($request, $brick->fresh());
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Okipa\LaravelBrickables\Models\Brick $brick
     *
     * @return \Illuminate\Http\RedirectResponse
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
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
        $brick = Brickables::castBrick($brick);
        $brick->moveOrderDown();

        return $this->sendBrickMovedResponse($request, $brick->fresh());
    }
}
