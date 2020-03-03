<?php

declare(strict_types=1);

namespace LenderSpender\SubstituteClassBinding\Routing;

interface UrlRoutable
{
    /**
     * Retrieve the model for a bound value.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public static function resolveRouteBinding($value);

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey();

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string;
}
