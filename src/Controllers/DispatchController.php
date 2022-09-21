<?php

namespace Okipa\LaravelBrickables\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Okipa\LaravelBrickables\Models\Brick;

class DispatchController extends Controller
{
    public function create(Request $request): View|Factory|Response|JsonResponse|RedirectResponse
    {
        $validator = Validator::make($request->only('brickable_type'), ['brickable_type' => ['required', 'string']]);
        if ($validator->fails()) {
            return redirect()->to($request->admin_panel_url)->withErrors($validator)->withInput();
        }

        return $this->dispatchRequest('create', $request);
    }

    protected function dispatchRequest(string $action, Request $request, ?Brick $brick = null): mixed
    {
        $brickable = $brick->brickable ?? app($request->brickable_type);
        $bricksController = $brickable->getBricksController();

        return $bricksController->callAction($action, $brick ? compact('brick', 'request') : compact('request'));
    }

    public function store(Request $request): Response|JsonResponse|RedirectResponse
    {
        return $this->dispatchRequest('store', $request);
    }

    public function edit(Brick $brick, Request $request): View
    {
        return $this->dispatchRequest('edit', $request, $brick);
    }

    public function update(Brick $brick, Request $request): Response|JsonResponse|RedirectResponse
    {
        return $this->dispatchRequest('update', $request, $brick);
    }

    public function destroy(Brick $brick, Request $request): Response|JsonResponse|RedirectResponse
    {
        return $this->dispatchRequest('destroy', $request, $brick);
    }

    public function moveUp(Brick $brick, Request $request): Response|JsonResponse|RedirectResponse
    {
        return $this->dispatchRequest('moveUp', $request, $brick);
    }

    public function moveDown(Brick $brick, Request $request): Response|JsonResponse|RedirectResponse
    {
        return $this->dispatchRequest('moveDown', $request, $brick);
    }
}
