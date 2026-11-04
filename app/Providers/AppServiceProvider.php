<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        View::composer('layouts.app', function ($view) {
            if (Auth::check() && Auth::user()->isOwnerOrAdmin()) {
                $user = Auth::user();
                $unreadCount = $user->unreadNotifications()->count();
                $recentNotifications = $user->notifications()->take(5)->get();

                $view->with([
                    'navbarUnreadNotificationsCount' => $unreadCount,
                    'navbarRecentNotifications' => $recentNotifications,
                ]);
            }
        });
    }
}
