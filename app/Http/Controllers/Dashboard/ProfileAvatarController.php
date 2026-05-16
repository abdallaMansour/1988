<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ProfileAvatar;
use Illuminate\Http\Request;

class ProfileAvatarController extends Controller
{
    public function index()
    {
        $this->ensureView();

        $profileAvatars = ProfileAvatar::query()
            ->with('media')
            ->withCount('users')
            ->latest()
            ->paginate(15);

        return view('dashboard.profile-avatars.index', compact('profileAvatars'));
    }

    public function create()
    {
        $this->ensureManage();

        return view('dashboard.profile-avatars.create');
    }

    public function store(Request $request)
    {
        $this->ensureManage();

        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,gif,svg,webp', 'max:4096'],
        ]);

        $profileAvatar = ProfileAvatar::create();

        $profileAvatar->addMediaFromRequest('image')->toMediaCollection('image');

        return redirect()
            ->route('dashboard.profile-avatars.index')
            ->with('success', 'تم إضافة صورة البروفايل بنجاح.');
    }

    public function edit(ProfileAvatar $profile_avatar)
    {
        $this->ensureManage();

        return view('dashboard.profile-avatars.edit', ['profileAvatar' => $profile_avatar]);
    }

    public function update(Request $request, ProfileAvatar $profile_avatar)
    {
        $this->ensureManage();

        $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpeg,png,gif,svg,webp', 'max:4096'],
        ]);

        if ($request->hasFile('image')) {
            $profile_avatar->clearMediaCollection('image');
            $profile_avatar->addMediaFromRequest('image')->toMediaCollection('image');
        }

        return redirect()
            ->route('dashboard.profile-avatars.index')
            ->with('success', 'تم تحديث صورة البروفايل بنجاح.');
    }

    public function destroy(ProfileAvatar $profile_avatar)
    {
        $this->ensureManage();

        $profile_avatar->users()->update(['profile_avatar_id' => null]);
        $profile_avatar->delete();

        return redirect()
            ->route('dashboard.profile-avatars.index')
            ->with('success', 'تم حذف صورة البروفايل بنجاح.');
    }

    private function ensureView(): void
    {
        $admin = auth('admin')->user();
        abort_unless($admin, 403);
        if ($admin->roles()->count() > 0 && ! $admin->hasRole('super_admin')) {
            abort_unless(
                $admin->hasPermission('profile-avatars.view') || $admin->hasPermission('profile-avatars.manage'),
                403,
                'ليس لديك صلاحية لعرض صور البروفايل'
            );
        }
    }

    private function ensureManage(): void
    {
        $admin = auth('admin')->user();
        abort_unless($admin, 403);
        if ($admin->roles()->count() > 0 && ! $admin->hasRole('super_admin')) {
            abort_unless($admin->hasPermission('profile-avatars.manage'), 403, 'ليس لديك صلاحية لإدارة صور البروفايل');
        }
    }
}
