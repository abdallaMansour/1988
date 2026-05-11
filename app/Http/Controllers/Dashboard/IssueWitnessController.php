<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\IssueWitness;
use Illuminate\Http\Request;

class IssueWitnessController extends Controller
{
    public function index(Issue $issue)
    {
        $witnesses = $issue->witnesses()->orderBy('sort_order')->orderBy('id')->get();

        return view('dashboard.issues.witnesses.index', compact('issue', 'witnesses'));
    }

    public function store(Request $request, Issue $issue)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpeg,png,gif,svg,webp', 'max:4096'],
        ]);

        $nextOrder = (int) $issue->witnesses()->max('sort_order') + 1;

        $witness = $issue->witnesses()->create([
            'title' => $validated['title'],
            'sort_order' => $nextOrder,
        ]);

        $witness->addMediaFromRequest('image')->toMediaCollection('image');

        return redirect()->route('dashboard.issues.witnesses.index', $issue)->with('success', 'تم إضافة الشاهد.');
    }

    public function destroy(Issue $issue, IssueWitness $witness)
    {
        if ((int) $witness->issue_id !== (int) $issue->getKey()) {
            abort(404);
        }

        IssueWitness::destroy($witness->id);

        return redirect()->route('dashboard.issues.witnesses.index', $issue)->with('success', 'تم حذف الشاهد.');
    }
}
