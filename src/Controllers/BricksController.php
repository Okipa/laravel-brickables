<?php

namespace Okipa\LaravelBrickables\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Okipa\LaravelBrickables\Facades\Brickables;
use Okipa\LaravelBrickables\Models\Brick;

class BricksController extends Controller
{
    public function create(Request $request): View
    {
        $brick = null;
        /** @var \Okipa\LaravelBrickables\Contracts\HasBrickables $model */
        $model = app($request->model_type)->findOrFail($request->model_id);
        /** @var \Okipa\LaravelBrickables\Abstracts\Brickable $brickable */
        $brickable = app($request->brickable_type);
        $adminPanelUrl = $request->admin_panel_url;

        return view($brickable->getFormViewPath(), compact('brick', 'model', 'brickable', 'adminPanelUrl'));
    }

    /**
     * @throws \Okipa\LaravelBrickables\Exceptions\InvalidBrickableClassException
     * @throws \Okipa\LaravelBrickables\Exceptions\NotRegisteredBrickableClassException
     * @throws \Okipa\LaravelBrickables\Exceptions\BrickableCannotBeHandledException
     */
    public function store(Request $request): Response|JsonResponse|RedirectResponse
    {
        /** @var \Okipa\LaravelBrickables\Contracts\HasBrickables $model */
        $model = app($request->model_type)->findOrFail($request->model_id);
        /** @var \Okipa\LaravelBrickables\Abstracts\Brickable $brickable */
        $brickable = app($request->brickable_type);
        $validated = $brickable->validateStoreInputs();
        $brick = $model->addBrick($request->brickable_type, $validated);
        $this->stored($request, $brick);

        return $this->sendBrickCreatedResponse($request, $brick);
    }

    public function edit(Brick $brick, Request $request): View
    {
        $brick = Brickables::castBrick($brick);
        /** @var \Okipa\LaravelBrickables\Contracts\HasBrickables $model */
        $model = $brick->model;
        /** @var \Okipa\LaravelBrickables\Abstracts\Brickable $brickable */
        $brickable = $brick->brickable;
        $adminPanelUrl = $request->admin_panel_url;

        return view($brickable->getFormViewPath(), compact('brick', 'model', 'brickable', 'adminPanelUrl'));
    }

    public function update(Brick $brick, Request $request): Response|JsonResponse|RedirectResponse
    {
        $brick = Brickables::castBrick($brick);
        $validated = $brick->brickable->validateUpdateInputs();
        $brick->data = $validated;
        $brick->save();
        $this->updated($request, $brick);

        return $this->sendBrickUpdatedResponse($request, $brick);
    }

    public function destroy(Brick $brick, Request $request): Response|JsonResponse|RedirectResponse
    {
        $brick = Brickables::castBrick($brick);
        $brickClone = clone $brick;
        $brick->delete();

        return $this->sendBrickDestroyedResponse($request, $brickClone);
    }

    public function moveUp(Brick $brick, Request $request): Response|JsonResponse|RedirectResponse
    {
        $brick = Brickables::castBrick($brick);
        $brick->moveOrderUp();

        return $this->sendBrickMovedResponse($request, $brick->fresh());
    }

    public function moveDown(Brick $brick, Request $request): Response|JsonResponse|RedirectResponse
    {
        $brick = Brickables::castBrick($brick);
        $brick->moveOrderDown();

        return $this->sendBrickMovedResponse($request, $brick->fresh());
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    protected function stored(Request $request, Brick $brick): void
    {
        // Execute additional treatment once the brick has been stored.
    }

    protected function sendBrickCreatedResponse(Request $request, Brick $brick): RedirectResponse
    {
        return redirect()->to($request->admin_panel_url)
            ->with('success', __('The entry :model > :brickable has been created.', [
                'brickable' => $brick->brickable->getLabel(),
                'model' => $brick->model->getReadableClassName(),
            ]));
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    protected function updated(Request $request, Brick $brick): void
    {
        // Execute additional treatment once the brick has been updated.
    }

    protected function sendBrickUpdatedResponse(Request $request, Brick $brick): Response|JsonResponse|RedirectResponse
    {
        return redirect()->back()->with('success', __('The entry :model > :brickable has been updated.', [
            'brickable' => $brick->brickable->getLabel(),
            'model' => $brick->model->getReadableClassName(),
        ]));
    }

    protected function sendBrickDestroyedResponse(
        Request $request,
        Brick $brick
    ): Response|JsonResponse|RedirectResponse {
        return redirect()->to($request->admin_panel_url)
            ->with('success', __('The entry :model > :brickable has been deleted.', [
                'brickable' => $brick->brickable->getLabel(),
                'model' => $brick->model->getReadableClassName(),
            ]));
    }

    protected function sendBrickMovedResponse(Request $request, Brick $brick): Response|JsonResponse|RedirectResponse
    {
        return redirect()->to($request->admin_panel_url);
    }
}
