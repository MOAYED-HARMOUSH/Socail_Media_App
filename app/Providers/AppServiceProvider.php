<?php

namespace App\Providers;

// use App\Models\Friend;
// use App\Observers\FriendObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Friend::observe(FriendObserver::class);
    }
}
