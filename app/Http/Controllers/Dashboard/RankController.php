<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Rank;
use Illuminate\Http\Request;

class RankController extends Controller
{
    public function index()
    {
        $admin = auth('admin')->user();
        abort_unless($admin, 403);
        if ($admin->roles()->count() > 0 && ! $admin->hasRole('super_admin')) {
            if (! $admin->hasPermission('ranks.view') && ! $admin->hasPermission('ranks.manage')) {
                abort(403, 'ليس لديك صلاحية لعرض الرانكات');
            }
        }

        $ranks = Rank::query()->orderBy('solved_issues_from')->paginate(15);

        return view('dashboard.ranks.index', compact('ranks'));
    }

    public function create()
    {
        $this->ensureManage();

        return view('dashboard.ranks.create');
    }

    public function store(Request $request)
    {
        $this->ensureManage();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'solved_issues_from' => ['required', 'integer', 'min:0'],
            'solved_issues_to' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,gif,svg,webp', 'max:4096'],
        ]);

        if ((int) $validated['solved_issues_from'] > (int) $validated['solved_issues_to']) {
            return back()->withErrors([
                'solved_issues_to' => 'يجب أن يكون الحد الأعلى أكبر من أو يساوي الحد الأدنى.',
            ])->withInput();
        }

        $rank = Rank::create([
            'name' => $validated['name'],
            'solved_issues_from' => $validated['solved_issues_from'],
            'solved_issues_to' => $validated['solved_issues_to'],
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->hasFile('image')) {
            $rank->addMediaFromRequest('image')->toMediaCollection('image');
        }

        return redirect()->route('dashboard.ranks.index')->with('success', 'تم إنشاء الرانك بنجاح.');
    }

    public function edit(Rank $rank)
    {
        $this->ensureManage();

        return view('dashboard.ranks.edit', compact('rank'));
    }

    public function update(Request $request, Rank $rank)
    {
        $this->ensureManage();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'solved_issues_from' => ['required', 'integer', 'min:0'],
            'solved_issues_to' => ['required', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,gif,svg,webp', 'max:4096'],
            'clear_image' => ['sometimes', 'boolean'],
        ]);

        if ((int) $validated['solved_issues_from'] > (int) $validated['solved_issues_to']) {
            return back()->withErrors([
                'solved_issues_to' => 'يجب أن يكون الحد الأعلى أكبر من أو يساوي الحد الأدنى.',
            ])->withInput();
        }

        $rank->update([
            'name' => $validated['name'],
            'solved_issues_from' => $validated['solved_issues_from'],
            'solved_issues_to' => $validated['solved_issues_to'],
            'is_active' => (bool) ($request->boolean('is_active')),
        ]);

        if ($request->boolean('clear_image')) {
            $rank->clearMediaCollection('image');
        }

        if ($request->hasFile('image')) {
            $rank->clearMediaCollection('image');
            $rank->addMediaFromRequest('image')->toMediaCollection('image');
        }

        return redirect()->route('dashboard.ranks.index')->with('success', 'تم تحديث الرانك بنجاح.');
    }

    public function destroy(Rank $rank)
    {
        $this->ensureManage();

        $rank->delete();

        return redirect()->route('dashboard.ranks.index')->with('success', 'تم حذف الرانك بنجاح.');
    }

    private function ensureManage(): void
    {
        $admin = auth('admin')->user();
        abort_unless($admin, 403);
        if ($admin->roles()->count() > 0 && ! $admin->hasRole('super_admin')) {
            abort_unless($admin->hasPermission('ranks.manage'), 403, 'ليس لديك صلاحية لإدارة الرانكات');
        }
    }
}
