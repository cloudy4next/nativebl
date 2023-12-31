<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->namespace('App\Http\Controllers\TMS')
                ->group(base_path('routes/applications/tms.php'));

            Route::middleware('web')
                ->namespace('App\Http\Controllers\TigerWeb')
                ->group(base_path('routes/applications/tigerweb.php'));

            Route::middleware('web')
                ->namespace('App\Http\Controllers\ToffeAnalytics')
                ->group(base_path('routes/applications/toffeanalytics.php'));

            Route::middleware('web')
                ->namespace('App\Http\Controllers\DBSSESim')
                ->group(base_path('routes/applications/dbssesim.php'));
            Route::middleware('web')
                ->namespace('App\Http\Controllers\DND')
                ->group(base_path('routes/applications/dnd.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
