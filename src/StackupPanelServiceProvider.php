<?php

namespace Stackup\Panel;

use Carbon\Laravel\ServiceProvider;

class StackupPanelServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([ __DIR__.'/config/auth.php' => config_path('auth.php')] ,  'files');
        $this->publishes( [__DIR__.'/Controllers/' => app_path('Http/Controllers/')],  'files');
        $this->publishes( [__DIR__.'/Middleware/' => app_path('Http/Middleware/')],  'files');
        $this->publishes( [__DIR__.'/kernel/' => app_path('Http/')],  'files');
        $this->publishes( [__DIR__.'/Models/' => app_path('Models/')],  'files');
        $this->publishes( [__DIR__.'/public/' => public_path('/')],  'files');
        $this->publishes( [__DIR__.'/resource/' => resource_path('/')],  'files');
        $this->publishes( [__DIR__.'/database/' => database_path('/')],  'files');
        $this->publishes( [__DIR__.'/routes/' => base_path('routes')],  'files');
    }
}