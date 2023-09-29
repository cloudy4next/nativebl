<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class BladeDirectivesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Blade::directive('hasPermission', function ($expression) {
            return "<?php if (auth()->check() && auth()->user()->hasPermission({$expression})) : ?>";
        });

        Blade::directive('endhasPermission', function () {
            return "<?php endif; ?>";
        });
    }
}
