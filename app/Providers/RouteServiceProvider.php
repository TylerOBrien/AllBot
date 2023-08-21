<?php

namespace App\Providers;

use App\Guards\Api\v1\ApiGuard;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{ RateLimiter, Route };

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This const is expected as part of the Laravel framework but is not
     * actually used.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route
     * configuration.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
        $this->bindLiteralMeRouteParam();
        $this->mapApiRoutes();
    }

    /**
     * Implicitly convert all {user} params of 'me' to the currently
     * authenticated user.
     *
     * @return void
     */
    protected function bindLiteralMeRouteParam(): void
    {
        Route::bind('user', function ($id) {
            return $id === 'me' ? auth()->user() : $id;
        });
    }

    /**
     * Define the api routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes(): void
    {
        Route::group(
            [
                'prefix' => config('api.version'),
                'domain' => config('api.domains.' . config('api.version')),
            ],
            function () {
                $hasToken = ApiGuard::get()->hasToken();
                $middlware = 'api.' . ($hasToken ? 'authenticated' : 'guest');
                $path = 'routes/Api/' . config('api.version') .'/api.php';

                Route::middleware($middlware)
                     ->group(base_path($path));
            },
        );
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
