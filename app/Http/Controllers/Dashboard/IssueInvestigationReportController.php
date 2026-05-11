<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\IssueInvestigationReport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IssueInvestigationReportController extends Controller
{
    public function index(Issue $issue)
    {
        $investigationReports = $issue->investigationReports()
            ->with('suspect')
            ->latest('id')
            ->get();

        return view('dashboard.issues.investigation-reports.index', compact('issue', 'investigationReports'));
    }

    public function create(Issue $issue)
    {
        return view('dashboard.issues.investigation-reports.create', [
            'issue' => $issue,
            'suspects' => $this->suspects($issue),
        ]);
    }

    public function store(Request $request, Issue $issue)
    {
        $validated = $request->validate($this->rules($issue));

        $issue->investigationReports()->create($validated);

        return redirect()->route('dashboard.issues.investigation-reports.index', $issue)->with('success', 'تم إضافة محضر التحقيق.');
    }

    public function edit(Issue $issue, IssueInvestigationReport $investigationReport)
    {
        $this->ensureReportBelongsToIssue($issue, $investigationReport);

        return view('dashboard.issues.investigation-reports.edit', [
            'issue' => $issue,
            'investigationReport' => $investigationReport,
            'suspects' => $this->suspects($issue),
        ]);
    }

    public function update(Request $request, Issue $issue, IssueInvestigationReport $investigationReport)
    {
        $this->ensureReportBelongsToIssue($issue, $investigationReport);

        $validated = $request->validate($this->rules($issue));

        $investigationReport->update($validated);

        return redirect()->route('dashboard.issues.investigation-reports.index', $issue)->with('success', 'تم تحديث محضر التحقيق.');
    }

    public function destroy(Issue $issue, IssueInvestigationReport $investigationReport)
    {
        $this->ensureReportBelongsToIssue($issue, $investigationReport);

        IssueInvestigationReport::destroy($investigationReport->id);

        return redirect()->route('dashboard.issues.investigation-reports.index', $issue)->with('success', 'تم حذف محضر التحقيق.');
    }

    private function rules(Issue $issue): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'report' => ['required', 'string'],
            'issue_hint_id' => [
                'required',
                'integer',
                Rule::exists('issue_hints', 'id')->where('issue_id', $issue->id),
            ],
        ];
    }

    private function suspects(Issue $issue)
    {
        return $issue->hints()->orderBy('sort_order')->orderBy('id')->get();
    }

    private function ensureReportBelongsToIssue(Issue $issue, IssueInvestigationReport $investigationReport): void
    {
        if ((int) $investigationReport->issue_id !== (int) $issue->getKey()) {
            abort(404);
        }
    }
}
