<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\IssueWitnessTestimony;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IssueWitnessTestimonyController extends Controller
{
    public function index(Issue $issue)
    {
        $witnessTestimonies = $issue->witnessTestimonies()
            ->with('witness')
            ->latest('id')
            ->get();

        return view('dashboard.issues.witness-testimonies.index', compact('issue', 'witnessTestimonies'));
    }

    public function create(Issue $issue)
    {
        return view('dashboard.issues.witness-testimonies.create', [
            'issue' => $issue,
            'witnesses' => $this->witnesses($issue),
        ]);
    }

    public function store(Request $request, Issue $issue)
    {
        $validated = $request->validate($this->rules($issue));

        $issue->witnessTestimonies()->create($validated);

        return redirect()->route('dashboard.issues.witness-testimonies.index', $issue)->with('success', 'تم إضافة شهادة الشاهد.');
    }

    public function edit(Issue $issue, IssueWitnessTestimony $witnessTestimony)
    {
        $this->ensureTestimonyBelongsToIssue($issue, $witnessTestimony);

        return view('dashboard.issues.witness-testimonies.edit', [
            'issue' => $issue,
            'witnessTestimony' => $witnessTestimony,
            'witnesses' => $this->witnesses($issue),
        ]);
    }

    public function update(Request $request, Issue $issue, IssueWitnessTestimony $witnessTestimony)
    {
        $this->ensureTestimonyBelongsToIssue($issue, $witnessTestimony);

        $validated = $request->validate($this->rules($issue));

        $witnessTestimony->update($validated);

        return redirect()->route('dashboard.issues.witness-testimonies.index', $issue)->with('success', 'تم تحديث شهادة الشاهد.');
    }

    public function destroy(Issue $issue, IssueWitnessTestimony $witnessTestimony)
    {
        $this->ensureTestimonyBelongsToIssue($issue, $witnessTestimony);

        IssueWitnessTestimony::destroy($witnessTestimony->id);

        return redirect()->route('dashboard.issues.witness-testimonies.index', $issue)->with('success', 'تم حذف شهادة الشاهد.');
    }

    private function rules(Issue $issue): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'report' => ['required', 'string'],
            'issue_witness_id' => [
                'required',
                'integer',
                Rule::exists('issue_witnesses', 'id')->where('issue_id', $issue->id),
            ],
        ];
    }

    private function witnesses(Issue $issue)
    {
        return $issue->witnesses()->orderBy('sort_order')->orderBy('id')->get();
    }

    private function ensureTestimonyBelongsToIssue(Issue $issue, IssueWitnessTestimony $witnessTestimony): void
    {
        if ((int) $witnessTestimony->issue_id !== (int) $issue->getKey()) {
            abort(404);
        }
    }
}
