<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\IssueEvidence;
use Illuminate\Http\Request;

class IssueEvidenceController extends Controller
{
    public function index(Issue $issue)
    {
        $evidences = $issue->evidences()->orderBy('sort_order')->orderBy('id')->get();

        return view('dashboard.issues.evidences.index', compact('issue', 'evidences'));
    }

    public function store(Request $request, Issue $issue)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpeg,png,gif,svg,webp', 'max:4096'],
        ]);

        $nextOrder = (int) $issue->evidences()->max('sort_order') + 1;

        $evidence = $issue->evidences()->create([
            'title' => $validated['title'],
            'sort_order' => $nextOrder,
        ]);

        $evidence->addMediaFromRequest('image')->toMediaCollection('image');

        return redirect()->route('dashboard.issues.evidences.index', $issue)->with('success', 'تم إضافة الدليل.');
    }

    public function destroy(Issue $issue, IssueEvidence $evidence)
    {
        if ((int) $evidence->issue_id !== (int) $issue->getKey()) {
            abort(404);
        }

        IssueEvidence::destroy($evidence->id);

        return redirect()->route('dashboard.issues.evidences.index', $issue)->with('success', 'تم حذف الدليل.');
    }
}
