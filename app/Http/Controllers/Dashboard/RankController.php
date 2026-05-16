<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Rank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $lastRank = Rank::query()->orderByDesc('solved_issues_from')->first();
        $suggestedFrom = 0;
        if ($lastRank) {
            $suggestedFrom = $lastRank->isOpenEnded()
                ? $lastRank->solved_issues_from + 1
                : $lastRank->solved_issues_to + 1;
        }

        return view('dashboard.ranks.create', compact('suggestedFrom'));
    }

    public function store(Request $request)
    {
        $this->ensureManage();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'solved_issues_from' => ['required', 'integer', 'min:0'],
        ]);

        $from = (int) $validated['solved_issues_from'];

        $previous = Rank::query()
            ->where('solved_issues_from', '<', $from)
            ->orderByDesc('solved_issues_from')
            ->first();

        if ($previous && ! $this->isValidFromAfterPrevious($from, $previous)) {
            return back()->withErrors([
                'solved_issues_from' => 'يجب أن تكون البداية أكبر من نهاية الرانك السابق.',
            ])->withInput();
        }

        DB::transaction(function () use ($validated, $from, $previous, $request) {
            if ($previous) {
                $previous->update(['solved_issues_to' => $from - 1]);
            }

            Rank::create([
                'name' => $validated['name'],
                'solved_issues_from' => $from,
                'solved_issues_to' => Rank::OPEN_END,
                'is_active' => $request->boolean('is_active'),
            ]);
        });

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
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $from = (int) $validated['solved_issues_from'];

        $previous = Rank::query()
            ->where('solved_issues_from', '<', $from)
            ->where('id', '!=', $rank->id)
            ->orderByDesc('solved_issues_from')
            ->first();

        $next = Rank::query()
            ->where('solved_issues_from', '>', $from)
            ->where('id', '!=', $rank->id)
            ->orderBy('solved_issues_from')
            ->first();

        if ($previous && $from <= $previous->solved_issues_from) {
            return back()->withErrors([
                'solved_issues_from' => 'يجب أن تكون البداية أكبر من بداية الرانك السابق.',
            ])->withInput();
        }

        if ($next && $from >= $next->solved_issues_from) {
            return back()->withErrors([
                'solved_issues_from' => 'يجب أن تكون البداية أقل من بداية الرانك التالي.',
            ])->withInput();
        }

        $solvedIssuesTo = $next
            ? $next->solved_issues_from - 1
            : Rank::OPEN_END;

        DB::transaction(function () use ($rank, $validated, $from, $previous, $solvedIssuesTo, $request) {
            if ($previous) {
                $previous->update(['solved_issues_to' => $from - 1]);
            }

            $rank->update([
                'name' => $validated['name'],
                'solved_issues_from' => $from,
                'solved_issues_to' => $solvedIssuesTo,
                'is_active' => (bool) $request->boolean('is_active'),
            ]);
        });

        return redirect()->route('dashboard.ranks.index')->with('success', 'تم تحديث الرانك بنجاح.');
    }

    public function destroy(Rank $rank)
    {
        $this->ensureManage();

        $rank->delete();

        return redirect()->route('dashboard.ranks.index')->with('success', 'تم حذف الرانك بنجاح.');
    }

    private function isValidFromAfterPrevious(int $from, Rank $previous): bool
    {
        $minFrom = $previous->isOpenEnded()
            ? $previous->solved_issues_from + 1
            : $previous->solved_issues_to + 1;

        return $from >= $minFrom;
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
