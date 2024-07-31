<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class OmiseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(config_path('omise.php'), 'omise');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
