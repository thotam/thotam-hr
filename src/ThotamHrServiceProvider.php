<?php

namespace Thotam\ThotamHr;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use Thotam\ThotamHr\Http\Livewire\HrLivewire;
use Thotam\ThotamHr\Http\Livewire\UpdateHrLivewire;
use Thotam\ThotamHr\Console\Commands\HR_Sync_Command;
use Thotam\ThotamHr\Http\Livewire\UpdateInfoLivewire;

class ThotamHrServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'thotam-hr');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'thotam-hr');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('thotam-hr.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/thotam-hr'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/thotam-hr'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/thotam-hr'),
            ], 'lang');*/

            // Registering package commands.
            $this->commands([
                HR_Sync_Command::class,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Seed Service Provider need on boot() method
        |--------------------------------------------------------------------------
        */
        $this->app->register(SeedServiceProvider::class);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'thotam-hr');

        // Register the main class to use with the facade
        $this->app->singleton('thotam-hr', function () {
            return new ThotamHr;
        });

        if (class_exists(Livewire::class)) {
            Livewire::component('thotam-hr::update-hr-livewire', UpdateHrLivewire::class);
            Livewire::component('thotam-hr::hr-livewire', HrLivewire::class);
            Livewire::component('thotam-hr::update-info-livewire', UpdateInfoLivewire::class);
        }
    }
}
