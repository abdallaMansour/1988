<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\Product;
use App\Models\ProfileAvatar;
use App\Models\Purchase;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserDashboardController extends Controller
{
    public function crimesFile()
    {
        $purchases = Purchase::query()
            ->where('user_id', auth()->id())
            ->where('status', 'paid')
            ->where('purchasable_type', Issue::class)
            ->where(function ($q) {
                $q->whereNull('gift_claim_token')
                    ->orWhereNotNull('gift_from_user_id');
            })
            ->with(['purchasable.media', 'giftFrom'])
            ->latest()
            ->paginate(12);

        return view('dashboard.user.crimes-file', compact('purchases'));
    }

    public function purchases()
    {
        $purchases = Purchase::query()
            ->where('user_id', auth()->id())
            ->where('status', 'paid')
            ->where(function ($q) {
                $q->whereNull('gift_claim_token')
                    ->orWhereNotNull('gift_from_user_id');
            })
            ->whereIn('purchasable_type', [Issue::class, Product::class])
            ->with(['purchasable', 'giftFrom'])
            ->latest()
            ->paginate(15);

        return view('dashboard.user.purchases', compact('purchases'));
    }

    public function notifications()
    {
        $user = auth()->user();

        $notifications = UserNotification::query()
            ->visibleTo($user)
            ->withExists(['reads as read_by_user' => fn ($q) => $q->where('user_id', $user->id)])
            ->latest()
            ->paginate(15);

        $unreadCount = UserNotification::query()
            ->visibleTo($user)
            ->whereDoesntHave('reads', fn ($q) => $q->where('user_id', $user->id))
            ->count();

        return view('dashboard.user.notifications', compact('notifications', 'unreadCount'));
    }

    public function showNotification(UserNotification $userNotification)
    {
        $user = auth()->user();
        abort_unless($userNotification->isVisibleTo($user), 404);

        $userNotification->markAsReadBy($user);

        return view('dashboard.user.notification-show', [
            'notification' => $userNotification,
        ]);
    }

    public function markAllNotificationsRead()
    {
        $user = auth()->user();

        $unread = UserNotification::query()
            ->visibleTo($user)
            ->whereDoesntHave('reads', fn ($q) => $q->where('user_id', $user->id))
            ->get();

        foreach ($unread as $notification) {
            $notification->markAsReadBy($user);
        }

        return redirect()
            ->route('dashboard.user.notifications')
            ->with('success', 'تم تعليم جميع الإشعارات كمقروءة.');
    }

    public function profile()
    {
        $user = auth()->user()->load('profileAvatar.media');
        $profileAvatars = ProfileAvatar::query()
            ->with('media')
            ->latest()
            ->get();

        return view('dashboard.user.profile', compact('user', 'profileAvatars'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $hasAvatars = ProfileAvatar::query()->exists();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'investigator_name' => ['required', 'string', 'max:255', Rule::unique('users', 'investigator_name')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'account_type' => ['required', Rule::in([User::ACCOUNT_PUBLIC, User::ACCOUNT_PRIVATE])],
            'country' => ['required', 'string', 'size:2', 'regex:/^[A-Za-z]{2}$/'],
            'profile_avatar_id' => [
                $hasAvatars ? 'required' : 'nullable',
                'integer',
                Rule::exists('profile_avatars', 'id'),
            ],
        ]);

        $user->update([
            'name' => $validated['name'],
            'investigator_name' => $validated['investigator_name'],
            'email' => $validated['email'],
            'account_type' => $validated['account_type'],
            'country' => strtoupper($validated['country']),
            'profile_avatar_id' => $validated['profile_avatar_id'] ?? null,
        ]);

        if (! empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        return redirect()
            ->route('dashboard.user.profile')
            ->with('success', 'تم تحديث الملف الشخصي بنجاح.');
    }

    private function placeholder(string $title)
    {
        return view('dashboard.user.placeholder', [
            'title' => $title,
            'message' => 'لا توجد بيانات حالياً — قريباً',
        ]);
    }
}
