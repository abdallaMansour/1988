<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::withCount('roles')->orderBy('group')->orderBy('name')->get()->groupBy('group');
        $roles = Role::with('permissions')->get();

        return view('dashboard.permissions.index', compact('permissions', 'roles'));
    }

    public function updateRolePermissions(Request $request)
    {
        $validated = $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role = Role::findOrFail($validated['role_id']);

        if ($role->slug === 'super_admin') {
            return back()->with('error', 'لا يمكن تعديل صلاحيات دور مدير النظام');
        }

        $role->permissions()->sync($validated['permissions'] ?? []);

        return back()->with('success', 'تم تحديث صلاحيات الدور بنجاح');
    }
}
