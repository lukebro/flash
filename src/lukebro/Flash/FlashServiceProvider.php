<?php

namespace Lukebro\Flash;

use Illuminate\Support\ServiceProvider;

class FlashServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register Flash services.
     *
     * @return void
     */
    public function register()
    {
        // Bind store interface to store implemenation. 
        $this->app->bind(
            'Lukebro\Flash\FlashStoreInterface',
            'Lukebro\Flash\FlashStore'
        );

        // Bind flash to LukeBro\Flash\FlashFactory;
        $this->app->bind('flash', function () {
            return $this->app->singleton('Lukebro\Flash\FlashFactory');
        });
    }

}
