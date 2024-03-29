<?php

declare(strict_types=1);

namespace LenderSpender\SubstituteClassBinding\Http\Middleware;

use Closure;
use Illuminate\Contracts\Container\Container;
use LenderSpender\SubstituteClassBinding\Routing\ImplicitClassRouteBinding;

class SubstituteClassBindings
{
    public function __construct(private Container $container)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var \Illuminate\Routing\Route $route */
        $route = $request->route();
        ImplicitClassRouteBinding::resolveForRoute($this->container, $route);

        return $next($request);
    }
}
