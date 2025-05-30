<?php

namespace App\Providers;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
<<<<<<< Updated upstream
        Schema::defaultStringLength(191);
=======
        
        if ($this->app->environment('local')) {
            URL::forceRootUrl(config('app.url')); 

            URL::forceScheme('http');
        }

        
>>>>>>> Stashed changes
    }
}
