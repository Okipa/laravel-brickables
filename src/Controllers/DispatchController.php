<?php

namespace Okipa\LaravelBrickables\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Okipa\LaravelBrickables\Models\Brick;

class DispatchController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->only('brickable_type'), ['brickable_type' => ['required', 'string']]);
        if ($validator->fails()) {
            return redirect()->to($request->admin_panel_url)->withErrors($validator)->withInput();
        }

        return $this->dispatchRequest('create', $request);
    }

    /**
     * @param string $action
     * @param \Illuminate\Http\Request $request
     * @param \Okipa\LaravelBrickables\Models\Brick|null $brick
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function dispatchRequest(string $action, Request $request, ?Brick $brick = null)
    {
        /** @var \Okipa\LaravelBrickables\Abstracts\Brickable $brickable */
        $brickable = $brick ? $brick->brickable : (new $request->brickable_type);
        /** @var \Okipa\LaravelBrickables\Controllers\BricksController $bricksController */
        $bricksController = $brickable->getBricksController();

        return $bricksController->callAction($action, $brick ? compact('brick', 'request') : compact('request'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        return $this->dispatchRequest('store', $request);
    }

    /**
     * @param \Okipa\LaravelBrickables\Models\Brick $brick
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Brick $brick, Request $request)
    {
        return $this->dispatchRequest('edit', $request, $brick);
    }

    /**
     * @param \Okipa\LaravelBrickables\Models\Brick $brick
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Brick $brick, Request $request)
    {
        return $this->dispatchRequest('update', $request, $brick);
    }

    /**
     * @param \Okipa\LaravelBrickables\Models\Brick $brick
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Brick $brick, Request $request)
    {
        return $this->dispatchRequest('destroy', $request, $brick);
    }

    /**
     * @param \Okipa\LaravelBrickables\Models\Brick $brick
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function moveUp(Brick $brick, Request $request)
    {
        return $this->dispatchRequest('moveUp', $request, $brick);
    }

    /**
     * @param \Okipa\LaravelBrickables\Models\Brick $brick
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function moveDown(Brick $brick, Request $request)
    {
        return $this->dispatchRequest('moveDown', $request, $brick);
    }
}
