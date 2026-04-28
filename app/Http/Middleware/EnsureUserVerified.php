<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user('admin')) {
            return $next($request);
        }

        $user = $request->user('web');
        if (!$user) {
            return $next($request);
        }
        if ($user->isBanned()) {
            auth('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('auth.login')->withErrors(['email' => 'حسابك محظور. يرجى التواصل مع الدعم الفني.']);
        }
        if ($user->isFullyVerified()) {
            return $next($request);
        }

        if ($request->routeIs('dashboard.verification.*')) {
            return $next($request);
        }

        return redirect()->route('dashboard.verification.index');
    }
}
