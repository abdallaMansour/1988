<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['subscriptions' => fn ($q) => $q->active()->latest('expires_at')])
            ->verified()
            ->latest()
            ->paginate(15);

        return view('dashboard.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('dashboard.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return redirect()->route('dashboard.users.index')->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('dashboard.users.index')->with('success', 'تم حذف المستخدم بنجاح');
    }

    public function ban(User $user)
    {
        $user->update(['banned_at' => now()]);

        return back()->with('success', 'تم حظر المستخدم بنجاح');
    }

    public function unban(User $user)
    {
        $user->update(['banned_at' => null]);

        return back()->with('success', 'تم إلغاء حظر المستخدم بنجاح');
    }
}
