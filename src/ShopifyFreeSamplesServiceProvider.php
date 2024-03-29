<?php

namespace RashediConsulting\ShopifyFreeSamples;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

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
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        /*$this->loadViewComponentsAs('ShopifyFreeSamples', [
            Views\Components\ProductList::class,
        ]);*/

        Livewire::component('ShopifyFreeSamples::product-list', \RashediConsulting\ShopifyFreeSamples\Http\Livewire\ProductList::class);
        Livewire::component('ShopifyFreeSamples::sample-set-list', \RashediConsulting\ShopifyFreeSamples\Http\Livewire\SampleSetList::class);
        Livewire::component('ShopifyFreeSamples::rule-set', \RashediConsulting\ShopifyFreeSamples\Http\Livewire\RuleSet::class);
        Livewire::component('ShopifyFreeSamples::rule-detail', \RashediConsulting\ShopifyFreeSamples\Http\Livewire\RuleDetail::class);

        //Blade::componentNamespace('RashediConsulting\\ShopifyFreeSamples\\Views\\Components', 'ShopifyFreeSamples');

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
        }

        $this->commands([
            \RashediConsulting\ShopifyFreeSamples\Console\Commands\UpdateProductCache::class
        ]);
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
