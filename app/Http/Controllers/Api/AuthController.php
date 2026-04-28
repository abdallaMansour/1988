<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ResetPasswordCodeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $user = User::where('email', $request->email)->first();
            if ($user->isBanned()) {
                return $this->sendError('Your account has been banned. Please contact the technical support.');
            }
            return $this->sendResponse([
                'user' => $user,
                'token' => $user->createToken('auth-token')->plainTextToken,
            ]);
        }
        return $this->sendError('The provided credentials do not match our records.');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->sendResponse($user->createToken('auth-token')->plainTextToken);
    }

    public function logout()
    {
        Auth::guard('api')->user()->tokens()->delete();

        return $this->sendSuccess('Logged out successfully.');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'exists:users,email'],
        ]);

        $email = $request->email;
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(15);

        DB::table('password_reset_codes')->where('email', $email)->delete();

        DB::table('password_reset_codes')->insert([
            'email' => $email,
            'code' => $code,
            'expires_at' => $expiresAt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = User::where('email', $email)->first();
        $user->notify(new ResetPasswordCodeNotification($code));

        return $this->sendSuccess('Verification code has been sent to your email.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'exists:users,email'],
            'code' => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        $record = DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if (! $record) {
            return $this->sendError('The verification code is invalid or has expired.');
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_codes')->where('email', $request->email)->delete();

        return $this->sendSuccess('Your password has been reset successfully. You can now login.');
    }
}
