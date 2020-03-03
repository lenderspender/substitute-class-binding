<?php

declare(strict_types=1);

namespace LenderSpender\SubstituteClassBinding\Http\Middleware;

use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Routing\Registrar;
use LenderSpender\SubstituteClassBinding\Routing\ImplicitClassRouteBinding;

class SubstituteClassBindings
{
    private Registrar $router;

    private Container $container;

    public function __construct(Registrar $router, Container $container)
    {
        $this->router = $router;
        $this->container = $container;
    }

    public function handle($request, Closure $next)
    {
        ImplicitClassRouteBinding::resolveForRoute($this->container, $request->route());

        return $next($request);
    }
}
