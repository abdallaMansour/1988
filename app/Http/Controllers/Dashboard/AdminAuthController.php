<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class AdminAuthController extends Controller
{
    public function login()
    {
        return view('dashboard.auth.login');
    }

    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $key = 'admin-login:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors([
                'email' => 'تم إرسال الكثير من طلبات الدخول. يرجى المحاولة مرة أخرى في ' . RateLimiter::availableIn($key) . ' ثانية.',
            ])->withInput($request->only('email'));
        }

        if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::clear($key);
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard.index'));
        }

        RateLimiter::hit($key);

        return back()->withErrors([
            'email' => 'البيانات المدخلة غير متطابقة مع البيانات المسجلة لدينا.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard.login');
    }
}
