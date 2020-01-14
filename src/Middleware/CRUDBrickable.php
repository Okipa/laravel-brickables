<?php

namespace Okipa\LaravelBrickables\Middleware;

use Closure;
use Illuminate\Http\Request;
use Okipa\LaravelBrickables\Abstracts\Brickable;
use Okipa\LaravelBrickables\Contracts\HasBrickables;
use Symfony\Component\HttpFoundation\Response;

class CRUDBrickable
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
    public function handle(Request $request, Closure $next)
    {
        if (! $request->brick) {
            if (! $request->model_type) {
                abort(Response::HTTP_FORBIDDEN, 'The model_type value is missing from the request.');
            }
            if (! (new $request->model_type) instanceof HasBrickables) {
                abort(
                    Response::HTTP_FORBIDDEN,
                    'The ' . $request->model_type . ' class should implement ' . HasBrickables::class . '.'
                );
            }
            if (! $request->model_id) {
                abort(Response::HTTP_FORBIDDEN, 'The model_id value is missing from the request.');
            }
            if ($request->brickable_type && ! (new $request->brickable_type) instanceof Brickable) {
                abort(
                    Response::HTTP_FORBIDDEN,
                    'The ' . $request->brickable_type . ' class should extend ' . Brickable::class . '.'
                );
            }
        }
        if (! $request->admin_panel_url) {
            abort(Response::HTTP_FORBIDDEN, 'The admin_panel_url value is missing from the request.');
        }

        return $next($request);
    }
}
