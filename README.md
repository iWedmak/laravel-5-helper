# laravel-5-helper
FormatResponce
Register Middleware, add this to app/Http/Kernel.php in middlewareGroups array in web, as last one of them: 
```php
iWedmak\Helper\Middleware\FormatResponce::class,
```
xCache
Register provider, add this to config/app.php in providers array: 
```php
iWedmak\Helper\Providers\CacheServiceProvider::class,
```
Register xcache driver, add this to config/cache.php in stores array: 
```php
'xcache' => [
    'driver' => 'xcache',
],
  
```
