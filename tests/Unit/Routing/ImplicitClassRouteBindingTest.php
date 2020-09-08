<?php

declare(strict_types=1);

namespace LenderSpender\SubstituteClassBinding\Tests\Unit\Routing;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use LenderSpender\SubstituteClassBinding\Routing\ImplicitClassRouteBinding;
use LenderSpender\SubstituteClassBinding\Routing\UrlRoutable;
use Orchestra\Testbench\TestCase;

class ImplicitClassRouteBindingTest extends TestCase
{
    public function test_class_is_bound_to_route(): void
    {
        $route = new Route('POST', '/bla/{fooBar}', function (FooBar $fooBar) {});

        $request = Request::create('/bla/valid', 'POST');
        $route->bind($request);

        ImplicitClassRouteBinding::resolveForRoute($this->app, $route);

        /** @var \LenderSpender\SubstituteClassBinding\Tests\Unit\Routing\FooBar $parameter */
        $parameter = $route->parameter('fooBar');
        self::assertEquals('valid', $parameter->id);
    }

    public function test_not_found_exception_is_thrown_when_class_does_not_exists(): void
    {
        $route = new Route('POST', '/bla/{fooBar}', function (FooBar $fooBar) {});

        $request = Request::create('/bla/invalid', 'POST');
        $route->bind($request);

        try {
            ImplicitClassRouteBinding::resolveForRoute($this->app, $route);
        } catch (ModelNotFoundException $e) {
            $class = FooBar::class;
            self::assertSame("No query results for model [{$class}] invalid", $e->getMessage());

            return;
        }

        self::fail('Should have thrown model not found exception');
    }
}

class FooBar implements UrlRoutable
{
    public string $id;

    /**
     * @param array<string, string> $values
     */
    public function __construct(array $values)
    {
        $this->id = $values['id'];
    }

    public static function resolveRouteBinding($value)
    {
        if ($value === 'valid') {
            return new self(['id' => $value]);
        }

        return null;
    }

    public function getRouteKey()
    {
        return $this->id;
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }
}
