# Laravel Substitute Class Binding

You can use this package to automatically resolve classes when used in routes.

Add the `LenderSpender\SubstituteClassBinding\Http\Middleware\SubstituteClassBindings` middleware to your `$middleware` array in `\App\Kernel`.

Add the `LenderSpender\SubstituteClassBinding\Routing\UrlRoutable` interface to the class you wish to be resolved.

```php
<?php 

declare(strict_types=1);

use LenderSpender\SubstituteClassBinding\Routing\UrlRoutable;

class Foo implements UrlRoutable
{
    public $id = 1;
    
    public function __construct(array $properties)
    {
        $this->id = $properties['id'];   
    }

    public static function resolveRouteBinding($value)
    {
        return new Foo(['id' => $value]);
    }

    public function getRouteKey()
    {
        return $this->id;
    }

    public function getRouteKeyName() : string
    {
        return 'id';
    }
}
```

Now the route will automatically be resolved when using it in the route.
```php
Route::get('/foo/{foo}', function (Foo $foo) {
    echo $foo->id; // 12345 when calling /foo/12345
});
```


