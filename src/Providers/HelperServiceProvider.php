<?php namespace iWedmak\Helper\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        foreach (glob('Mate/*.php') as $file) {
            require_once $file;
        }
    }
}
