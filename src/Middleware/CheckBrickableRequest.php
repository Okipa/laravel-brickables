<?php

namespace Okipa\LaravelBrickables\Middleware;

use Closure;
use Illuminate\Http\Request;
use Okipa\LaravelBrickables\Abstracts\Brickable;
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
    public function handle(Request $request, Closure $next)
    {
        $request->brick ? $this->withBrickChecks($request) : $this->withoutBrickChecks($request);
        if (! $request->admin_panel_url) {
            abort(Response::HTTP_FORBIDDEN, 'Missing admin panel url.');
        }

        return $next($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     */
    protected function withBrickChecks(Request $request): void
    {
        if (! $request->brick instanceof Brick) {
            abort(Response::HTTP_FORBIDDEN, get_class($request->brick) . ' should extends ' . Brick::class . '.');
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     */
    protected function withoutBrickChecks(Request $request): void
    {
        if (! $request->model_type) {
            abort(Response::HTTP_FORBIDDEN, 'Missing model type.');
        }
        if (! (new $request->model_type) instanceof HasBrickables) {
            abort(Response::HTTP_FORBIDDEN, $request->model_type . ' should implements ' . HasBrickables::class . '.');
        }
        if (! $request->model_id) {
            abort(Response::HTTP_FORBIDDEN, 'Missing model id.');
        }
        if ($request->brickable_type && ! (new $request->brickable_type) instanceof Brickable) {
            abort(Response::HTTP_FORBIDDEN, $request->brickable_type . ' should extends ' . Brickable::class . '.');
        }
    }
}
