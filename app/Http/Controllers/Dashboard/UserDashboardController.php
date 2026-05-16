<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\Product;
use App\Models\ProfileAvatar;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserDashboardController extends Controller
{
    public function crimesFile()
    {
        return $this->placeholder('ملف الجرائم');
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

    public function friends()
    {
        return $this->placeholder('اصدقائي');
    }

    public function notifications()
    {
        return $this->placeholder('الإشعارات');
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
