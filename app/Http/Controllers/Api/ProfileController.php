<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function getProfile()
    {
        $user = Auth::guard('api')->user();
        return $this->sendResponse([
            'user' => $user,
            'subscription' => $user->activeSubscription,
            'package' => $user->activeSubscription?->package->allData(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . Auth::guard('api')->user()->id],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user = Auth::guard('api')->user();
        $user->update($request->only('name', 'email', 'phone'));

        if ($request->has('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return $this->sendSuccess('Profile updated successfully.');
    }
}
