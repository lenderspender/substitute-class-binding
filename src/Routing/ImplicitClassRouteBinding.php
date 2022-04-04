<?php

declare(strict_types=1);

namespace LenderSpender\SubstituteClassBinding\Routing;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Routing\ImplicitRouteBinding;

class ImplicitClassRouteBinding extends ImplicitRouteBinding
{
    /**
     * @param \Illuminate\Contracts\Container\Container $container
     * @param \Illuminate\Routing\Route                 $route
     */
    public static function resolveForRoute($container, $route)
    {
        $parameters = $route->parameters();

        foreach ($route->signatureParameters([UrlRoutable::class]) as $parameter) {
            if (! $parameterName = static::getParameterName($parameter->name, $parameters)) {
                continue;
            }

            $parameterValue = $parameters[$parameterName];

            if ($parameterValue instanceof UrlRoutable) {
                continue;
            }

            $className = $parameter->getClass()->name;

            if (! $class = $className::resolveRouteBinding($parameterValue)) {
                throw (new ModelNotFoundException())->setModel($className, [$parameterValue]);
            }

            $route->setParameter($parameterName, $class);
        }
    }
}
