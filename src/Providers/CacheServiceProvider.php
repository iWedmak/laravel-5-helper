<?php namespace iWedmak\Helper\Providers;

use Illuminate\Support\ServiceProvider;
use Cache;
use iWedmak\Helper\Adapters\XCacheStore;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Cache::extend('xcache', function($app)
        {
            return Cache::repository(new XCacheStore);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        
    }
}
