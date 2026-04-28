<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::with('roles')->latest()->paginate(15);

        return view('dashboard.admins.index', compact('admins'));
    }

    public function create()
    {
        $roles = Role::with('permissions')->get();

        return view('dashboard.admins.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:admins,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $admin = Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        $admin->roles()->sync($validated['roles'] ?? []);

        return redirect()->route('dashboard.admins.index')->with('success', 'تم إنشاء الأدمن بنجاح');
    }

    public function edit(Admin $admin)
    {
        $roles = Role::with('permissions')->get();

        return view('dashboard.admins.edit', compact('admin', 'roles'));
    }

    public function update(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:admins,email,' . $admin->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $admin->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $admin->update(['password' => $validated['password']]);
        }

        $admin->roles()->sync($validated['roles'] ?? []);

        return redirect()->route('dashboard.admins.index')->with('success', 'تم تحديث الأدمن بنجاح');
    }

    public function destroy(Admin $admin)
    {
        if ($admin->id === auth('admin')->id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك');
        }

        $admin->delete();

        return redirect()->route('dashboard.admins.index')->with('success', 'تم حذف الأدمن بنجاح');
    }
}
