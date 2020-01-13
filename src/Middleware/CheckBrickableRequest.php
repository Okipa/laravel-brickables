<?php

namespace Okipa\LaravelBrickables\Middleware;

use Okipa\LaravelBrickables\Contracts\HasBrickables;
use Okipa\LaravelBrickables\Models\Brick;
use Symfony\Component\HttpFoundation\Response;

class CheckBrickableRequest
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        if ($request->brick && ! $request->brick instanceof Brick) {
            abort(Response::HTTP_FORBIDDEN, get_class($request->brick) . ' should extends ' . Brick::class . '.');
        }
        if (! $request->brick && ! $request->model_type) {
            abort(Response::HTTP_FORBIDDEN, 'Missing model type.');
        }
        if (! $request->brick && ! (new $request->model_type) instanceof HasBrickables) {
            abort(Response::HTTP_FORBIDDEN, $request->model_type . ' should implements ' . HasBrickables::class . '.');
        }
        if (! $request->brick && ! $request->model_id) {
            abort(Response::HTTP_FORBIDDEN, 'Missing model id.');
        }
        if (! $request->admin_panel_url) {
            abort(Response::HTTP_FORBIDDEN, 'Missing admin panel url.');
        }

        return $next($request);
    }
}
