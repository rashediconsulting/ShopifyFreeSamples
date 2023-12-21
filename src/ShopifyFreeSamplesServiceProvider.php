<?php

namespace RashediConsulting\ShopifyFreeSamples;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class ShopifyFreeSamplesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'ShopifyFreeSamples');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ShopifyFreeSamples');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->loadViewComponentsAs('ShopifyFreeSamples', [
            App\Views\Components\ProductList::class,
        ]);
        //Blade::componentNamespace('RashediConsulting\\ShopifyFreeSamples\\App\\Views\\Components', 'ShopifyFreeSamples');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('shopifyfreesamples.php'),
            ], 'config');

            // Publishing the views.
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/shopifyfreesamples'),
            ], 'views');

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/shopifyfreesamples'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/shopifyfreesamples'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'shopifyfreesamples');

        // Register the main class to use with the facade
        $this->app->singleton('shopifyfreesamples', function () {
            return new ShopifyFreeSamples;
        });
    }
}
