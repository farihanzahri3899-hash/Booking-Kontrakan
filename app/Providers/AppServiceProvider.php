<?php

namespace App\Providers;

use App\Models\Complaint;
use App\Models\Conversation;
use App\Models\Favorite;
use App\Models\Message;
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
            if (Auth::check()) {
                $user = Auth::user();

                // Favorites / Wishlist count
                $favoritesCount = Favorite::where('user_id', $user->id)->count();

                // Unread messages count for this user
                $conversationIds = Conversation::where('tenant_id', $user->id)
                    ->orWhere('owner_id', $user->id)
                    ->pluck('id');

                $unreadMessagesCount = Message::whereIn('conversation_id', $conversationIds)
                    ->where('sender_id', '!=', $user->id)
                    ->where('is_read', false)
                    ->count();

                // Complaints count
                if ($user->isOwnerOrAdmin()) {
                    $ownerPropertyIds = $user->properties()->pluck('id');
                    $pendingComplaintsCount = Complaint::whereIn('property_id', $ownerPropertyIds)
                        ->whereIn('status', ['pending', 'in_progress'])
                        ->count();
                } else {
                    $pendingComplaintsCount = Complaint::where('user_id', $user->id)
                        ->whereIn('status', ['pending', 'in_progress'])
                        ->count();
                }

                $viewData = [
                    'navbarFavoritesCount' => $favoritesCount,
                    'navbarUnreadMessagesCount' => $unreadMessagesCount,
                    'navbarPendingComplaintsCount' => $pendingComplaintsCount,
                ];

                if ($user->isOwnerOrAdmin()) {
                    $unreadCount = $user->unreadNotifications()->count();
                    $recentNotifications = $user->notifications()->take(5)->get();

                    $viewData['navbarUnreadNotificationsCount'] = $unreadCount;
                    $viewData['navbarRecentNotifications'] = $recentNotifications;
                }

                $view->with($viewData);
            }
        });
    }
}
