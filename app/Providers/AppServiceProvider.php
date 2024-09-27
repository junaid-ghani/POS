<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    public function boot(): void
    {
        view()->composer('*', function ($view) {            
            $settings = Setting::first();
            $view->with('settings', $settings);
        });
    }
}
