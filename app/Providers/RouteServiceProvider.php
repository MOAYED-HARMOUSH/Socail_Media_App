<?php

namespace App\Providers;

use App\Http\Controllers\Api\CommentController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::name('auth.')
                ->middleware('api')
                ->controller('App\Http\Controllers\Api\AuthController')
                ->prefix('api/auth')
                ->group(base_path('routes/Api/auth.php'));

            Route::name('users.')
                ->middleware(['api', 'auth:sanctum'])
                ->controller('App\Http\Controllers\Api\UserController')
                ->prefix('api/users')
                ->group(base_path('routes/Api/users.php'));

            Route::name('posts.')
                ->middleware(['api', 'auth:sanctum'])
                ->controller('App\Http\Controllers\Api\PostController')
                ->prefix('api/posts')
                ->group(base_path('routes/Api/posts.php'));

            Route::name('comments.')
                ->middleware(['api', 'auth:sanctum'])
                ->controller(CommentController::class)
                ->prefix('api/comments')
                ->group(base_path('routes/Api/comments.php'));
        });
    }
}
