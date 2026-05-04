<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\IssueHint;
use Illuminate\Http\Request;

class IssueHintController extends Controller
{
    public function index(Issue $issue)
    {
        $hints = $issue->hints()->orderBy('sort_order')->orderBy('id')->get();

        return view('dashboard.issues.hints.index', compact('issue', 'hints'));
    }

    public function store(Request $request, Issue $issue)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,gif,svg,webp', 'max:4096'],
        ]);

        $nextOrder = (int) $issue->hints()->max('sort_order') + 1;

        $hint = $issue->hints()->create([
            'sort_order' => $nextOrder,
        ]);

        $hint->addMediaFromRequest('image')->toMediaCollection('image');

        return redirect()->route('dashboard.issues.hints.index', $issue)->with('success', 'تم إضافة التلميح.');
    }

    public function destroy(Issue $issue, IssueHint $hint)
    {
        if ((int) $hint->issue_id !== (int) $issue->getKey()) {
            abort(404);
        }

        IssueHint::destroy($hint->id);

        return redirect()->route('dashboard.issues.hints.index', $issue)->with('success', 'تم حذف التلميح.');
    }
}
