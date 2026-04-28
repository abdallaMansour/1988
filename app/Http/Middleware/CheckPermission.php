<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $admin = $request->user('admin');

        if (!$admin) {
            abort(403, 'غير مصرح');
        }

        if ($admin->hasRole('super_admin')) {
            return $next($request);
        }

        if ($admin->roles()->count() === 0) {
            return $next($request);
        }

        if (!$admin->hasPermission($permission)) {
            abort(403, 'ليس لديك صلاحية للوصول لهذه الصفحة');
        }

        return $next($request);
    }
}
