<?php

namespace App\Providers;

use App\Enums\Modules;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{

    private $moduleList = [
        Modules::AUTH,
        Modules::COURSE,
        Modules::USER,
        Modules::STATS,
        Modules::NOTES,
        Modules::REVIEWS,
        Modules::COURSE_START,
        Modules::SECTIONS
    ];

    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

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

            foreach ($this->moduleList as $module) {
                Route::middleware('api')
                    ->prefix('api' . "/" . strtolower($module))
                    ->group(app_path() . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . "routes.php");
            }

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
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
