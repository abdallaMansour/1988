<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserNotificationController extends Controller
{
    public function index()
    {
        $this->ensureView();

        $notifications = UserNotification::query()
            ->with('admin')
            ->withCount('recipients')
            ->latest()
            ->paginate(15);

        return view('dashboard.user-notifications.index', compact('notifications'));
    }

    public function create()
    {
        $this->ensureManage();

        $users = User::query()
            ->whereNull('banned_at')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'investigator_name']);

        return view('dashboard.user-notifications.create', compact('users'));
    }

    public function store(Request $request)
    {
        $this->ensureManage();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'audience' => ['required', Rule::in(['all', 'selected'])],
            'user_ids' => ['required_if:audience,selected', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $sendToAll = $validated['audience'] === 'all';
        $userIds = $sendToAll ? [] : array_values(array_unique($validated['user_ids'] ?? []));

        DB::transaction(function () use ($validated, $sendToAll, $userIds) {
            $notification = UserNotification::create([
                'title' => $validated['title'],
                'message' => $validated['message'],
                'admin_id' => auth('admin')->id(),
                'send_to_all' => $sendToAll,
            ]);

            if (! $sendToAll && $userIds !== []) {
                $notification->recipients()->attach($userIds);
            }
        });

        return redirect()
            ->route('dashboard.user-notifications.index')
            ->with('success', 'تم إرسال الإشعار بنجاح.');
    }

    public function show(UserNotification $user_notification)
    {
        $this->ensureView();

        $user_notification->load(['admin', 'recipients']);

        return view('dashboard.user-notifications.show', [
            'notification' => $user_notification,
        ]);
    }

    public function destroy(UserNotification $user_notification)
    {
        $this->ensureManage();

        $user_notification->delete();

        return redirect()
            ->route('dashboard.user-notifications.index')
            ->with('success', 'تم حذف الإشعار بنجاح.');
    }

    private function ensureView(): void
    {
        $admin = auth('admin')->user();
        abort_unless($admin, 403);
        if ($admin->roles()->count() > 0 && ! $admin->hasRole('super_admin')) {
            abort_unless(
                $admin->hasPermission('notifications.view') || $admin->hasPermission('notifications.manage'),
                403,
                'ليس لديك صلاحية لعرض الإشعارات'
            );
        }
    }

    private function ensureManage(): void
    {
        $admin = auth('admin')->user();
        abort_unless($admin, 403);
        if ($admin->roles()->count() > 0 && ! $admin->hasRole('super_admin')) {
            abort_unless($admin->hasPermission('notifications.manage'), 403, 'ليس لديك صلاحية لإدارة الإشعارات');
        }
    }
}
