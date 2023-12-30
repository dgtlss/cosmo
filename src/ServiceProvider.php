<?php
    namespace Dgtlss\Cosmo;

    require_once __DIR__.'/helpers.php';


    class ServiceProvider extends \Illuminate\Support\ServiceProvider {



        public function boot()
        {
            // Load the routes for the error pages
            $this->loadRoutesFrom(__DIR__.'/Routes/web.php');

            // Load the views for the error pages
            $this->loadViewsFrom(__DIR__.'/Resources/views', 'cosmo');

            // Publish the views for the error pages
            $this->publishes([
                __DIR__.'/Resources/views' => resource_path('views/vendor/cosmo'),
            ]);

            // Publish the public assets for the error pages
            $this->publishes([
                __DIR__.'/Public' => public_path('vendor/cosmo'),
            ], 'public');

            // Load the migrations
            $this->loadMigrationsFrom(__DIR__ . '/Migrations');

            // Setup the config for cosmo
            $this->setupConfig(); // Load config
            if ($this->app->runningInConsole()) {
                $this->commands([
                    /* Commands */
                ]);
            }
        }

        public function register()
        {            
            // Register the CosmoController
            $this->app->make('Dgtlss\Cosmo\Controllers\CosmoController');
        }

        protected function setupConfig(){

            $configPath = __DIR__ . '/../config/cosmo.php';
            $this->publishes([$configPath => $this->getConfigPath()], 'config');
    
        }

        protected function getConfigPath()
        {
            return config_path('cosmo.php');
        }

        protected function publishConfig($configPath)
        {
            $this->publishes([$configPath => config_path('cosmo.php')], 'config');
        }


    }