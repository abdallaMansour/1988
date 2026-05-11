<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\IssueForensicReport;
use Illuminate\Http\Request;

class IssueForensicReportController extends Controller
{
    public function index(Issue $issue)
    {
        $forensicReports = $issue->forensicReports()
            ->latest('id')
            ->get();

        return view('dashboard.issues.forensic-reports.index', compact('issue', 'forensicReports'));
    }

    public function create(Issue $issue)
    {
        return view('dashboard.issues.forensic-reports.create', compact('issue'));
    }

    public function store(Request $request, Issue $issue)
    {
        $validated = $request->validate($this->rules());

        $issue->forensicReports()->create($validated);

        return redirect()->route('dashboard.issues.forensic-reports.index', $issue)->with('success', 'تم إضافة تقرير الطب الشرعي.');
    }

    public function edit(Issue $issue, IssueForensicReport $forensicReport)
    {
        $this->ensureReportBelongsToIssue($issue, $forensicReport);

        return view('dashboard.issues.forensic-reports.edit', compact('issue', 'forensicReport'));
    }

    public function update(Request $request, Issue $issue, IssueForensicReport $forensicReport)
    {
        $this->ensureReportBelongsToIssue($issue, $forensicReport);

        $validated = $request->validate($this->rules());

        $forensicReport->update($validated);

        return redirect()->route('dashboard.issues.forensic-reports.index', $issue)->with('success', 'تم تحديث تقرير الطب الشرعي.');
    }

    public function destroy(Issue $issue, IssueForensicReport $forensicReport)
    {
        $this->ensureReportBelongsToIssue($issue, $forensicReport);

        IssueForensicReport::destroy($forensicReport->id);

        return redirect()->route('dashboard.issues.forensic-reports.index', $issue)->with('success', 'تم حذف تقرير الطب الشرعي.');
    }

    private function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'report' => ['required', 'string'],
        ];
    }

    private function ensureReportBelongsToIssue(Issue $issue, IssueForensicReport $forensicReport): void
    {
        if ((int) $forensicReport->issue_id !== (int) $issue->getKey()) {
            abort(404);
        }
    }
}
