<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\MediaDepartment;
use App\Models\User;
use App\Notifications\ResetPasswordCodeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class UserAuthController extends Controller
{
    public function login()
    {
        $media = MediaDepartment::get();

        return view('website.auth.login', compact('media'));
    }

    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $key = 'user-login:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors([
                'email' => __('Too many login attempts. Please try again in :seconds seconds.', [
                    'seconds' => RateLimiter::availableIn($key),
                ]),
            ])->withInput($request->only('email'));
        }

        if (Auth::guard('web')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $user = Auth::guard('web')->user();
            if ($user->isBanned()) {
                Auth::guard('web')->logout();
                return back()->withErrors([
                    'email' => __('حسابك محظور. يرجى التواصل مع الدعم الفني.'),
                ])->withInput($request->only('email'));
            }
            RateLimiter::clear($key);
            $request->session()->regenerate();

            return redirect()->intended(route('website.landing-page'));
        }

        RateLimiter::hit($key);

        return back()->withErrors([
            'email' => __('The provided credentials do not match our records.'),
        ])->withInput($request->only('email'));
    }

    public function register()
    {
        $media = MediaDepartment::get();

        return view('website.auth.register', compact('media'));
    }

    public function processRegister(Request $request)
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

        Auth::guard('web')->login($user);

        return redirect()->route('website.landing-page');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('website.landing-page');
    }

    public function forgotPassword()
    {
        return view('website.auth.forgot-password');
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

        return redirect()->route('auth.reset-password', ['email' => $email])
            ->with('status', __('Verification code has been sent to your email.'));
    }

    public function resetPassword(Request $request)
    {
        $email = $request->query('email');

        return view('website.auth.reset-password', compact('email'));
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
            return back()->withErrors([
                'code' => __('The verification code is invalid or has expired.'),
            ])->withInput($request->only('email'));
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_codes')->where('email', $request->email)->delete();

        return redirect()->route('auth.login')
            ->with('status', __('Your password has been reset successfully. You can now login.'));
    }
}
