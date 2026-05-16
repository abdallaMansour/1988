<?php

namespace App\View\Composers;

use App\Models\UserNotification;
use Illuminate\View\View;

class DashboardLayoutComposer
{
    public function compose(View $view): void
    {
        $defaultAvatar = asset('assets/img/avatars/1.png');
        $avatarUrl = $defaultAvatar;
        $navbarNotifications = collect();
        $unreadNotificationsCount = 0;
        $showUserNotifications = false;

        if (auth('web')->check() && ! auth('admin')->check()) {
            $user = auth('web')->user();
            $user->loadMissing('profileAvatar.media');
            $showUserNotifications = true;

            if ($url = $user->avatarUrl()) {
                $avatarUrl = $url;
            }

            $navbarNotifications = UserNotification::query()
                ->visibleTo($user)
                ->withExists(['reads as read_by_user' => fn ($q) => $q->where('user_id', $user->id)])
                ->latest()
                ->limit(8)
                ->get();

            $unreadNotificationsCount = UserNotification::query()
                ->visibleTo($user)
                ->whereDoesntHave('reads', fn ($q) => $q->where('user_id', $user->id))
                ->count();
        }

        $view->with([
            'dashboardAvatarUrl' => $avatarUrl,
            'dashboardNavbarNotifications' => $navbarNotifications,
            'dashboardUnreadNotificationsCount' => $unreadNotificationsCount,
            'dashboardShowUserNotifications' => $showUserNotifications,
        ]);
    }
}
