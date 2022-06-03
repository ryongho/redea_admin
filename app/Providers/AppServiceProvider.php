<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
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
        dd(env('FORCE_HTTPS'));
        if(env('FORCE_HTTPS',true)) { // Default value should be false for local server
            \URL::forceScheme('https');
        }

    }
}
