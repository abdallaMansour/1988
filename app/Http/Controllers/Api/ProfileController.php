<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function getProfile()
    {
        $user = Auth::guard('api')->user();
        $user->load('profileAvatar.media');

        return $this->sendResponse([
            'user' => $user,
            'avatar_url' => $user->avatarUrl(),
            'subscription' => $user->activeSubscription,
            'package' => $user->activeSubscription?->package->allData(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::guard('api')->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'investigator_name' => ['required', 'string', 'max:255', 'unique:users,investigator_name,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
            'profile_avatar_id' => ['nullable', 'integer', 'exists:profile_avatars,id'],
            'account_type' => ['required', Rule::in([User::ACCOUNT_PUBLIC, User::ACCOUNT_PRIVATE])],
            'country' => ['required', 'string', 'size:2', 'regex:/^[A-Za-z]{2}$/'],
        ]);

        $user->update([
            ...$request->only('name', 'email', 'investigator_name', 'profile_avatar_id', 'account_type'),
            'country' => strtoupper($request->country),
        ]);

        if ($request->has('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return $this->sendSuccess('Profile updated successfully.');
    }
}
