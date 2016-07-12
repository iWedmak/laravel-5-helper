# laravel-5-helper
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
