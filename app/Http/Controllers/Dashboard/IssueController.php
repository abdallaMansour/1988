<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IssueController extends Controller
{
    public function index()
    {
        if (auth('admin')->check()) {
            if (! auth('admin')->user()->hasPermission('issues.view')) {
                return abort(403, 'ليس لديك صلاحية لعرض الجرائم');
            }
        }

        $issues = Issue::with('relatedIssue')->latest('id')->paginate(15);

        return view('dashboard.issues.index', compact('issues'));
    }

    public function create()
    {
        $relatedIssues = Issue::query()->orderBy('title', 'asc')->get(['id', 'title']);

        return view('dashboard.issues.create', [
            'relatedIssues' => $relatedIssues,
            'languagesOptions' => $this->languagesOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $issue = Issue::create([
            'title' => $validated['title'],
            'purchase_price_before_discount' => $validated['purchase_price_before_discount'],
            'purchase_price_after_discount' => $validated['purchase_price_after_discount'],
            'is_linked_to_novel' => (bool) $validated['is_linked_to_novel'],
            'is_active' => (bool) $validated['is_active'],
            'languages' => $validated['languages'],
            'details' => $validated['details'] ?? null,
            'is_related_to_another_issue' => (bool) $validated['is_related_to_another_issue'],
            'related_issue_id' => $this->resolveRelatedIssueId($validated),
        ]);

        if ($request->hasFile('main_image')) {
            $issue->addMediaFromRequest('main_image')->toMediaCollection('main_image');
        }

        return redirect()->route('dashboard.issues.index')->with('success', 'تم إنشاء الجريمة بنجاح.');
    }

    public function edit(Issue $issue)
    {
        $relatedIssues = Issue::query()->where('id', '!=', $issue->id)->orderBy('title', 'asc')->get(['id', 'title']);

        return view('dashboard.issues.edit', [
            'issue' => $issue,
            'relatedIssues' => $relatedIssues,
            'languagesOptions' => $this->languagesOptions(),
        ]);
    }

    public function update(Request $request, Issue $issue)
    {
        $validated = $request->validate($this->rules($issue));

        $issue->update([
            'title' => $validated['title'],
            'purchase_price_before_discount' => $validated['purchase_price_before_discount'],
            'purchase_price_after_discount' => $validated['purchase_price_after_discount'],
            'is_linked_to_novel' => (bool) $validated['is_linked_to_novel'],
            'is_active' => (bool) $validated['is_active'],
            'languages' => $validated['languages'],
            'details' => $validated['details'] ?? null,
            'is_related_to_another_issue' => (bool) $validated['is_related_to_another_issue'],
            'related_issue_id' => $this->resolveRelatedIssueId($validated),
        ]);

        if ($request->hasFile('main_image')) {
            $issue->clearMediaCollection('main_image');
            $issue->addMediaFromRequest('main_image')->toMediaCollection('main_image');
        }

        return redirect()->route('dashboard.issues.index')->with('success', 'تم تحديث الجريمة بنجاح.');
    }

    public function destroy(Issue $issue)
    {
        Issue::destroy($issue->id);

        return redirect()->route('dashboard.issues.index')->with('success', 'تم حذف الجريمة بنجاح.');
    }

    private function rules(?Issue $issue = null): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'main_image' => ['nullable', 'image', 'mimes:jpeg,png,gif,svg,webp', 'max:4096'],
            'purchase_price_before_discount' => ['required', 'numeric', 'min:0'],
            'purchase_price_after_discount' => ['required', 'numeric', 'min:0', 'lte:purchase_price_before_discount'],
            'is_linked_to_novel' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
            'languages' => ['required', 'array', 'min:1'],
            'languages.*' => ['required', 'string', Rule::exists('languages', 'code')],
            'details' => ['nullable', 'string'],
            'is_related_to_another_issue' => ['required', 'boolean'],
            'related_issue_id' => [
                'nullable',
                'required_if:is_related_to_another_issue,1',
                'integer',
                Rule::exists('issues', 'id'),
                ...($issue ? [Rule::notIn([$issue->id])] : []),
            ],
        ];
    }

    private function resolveRelatedIssueId(array $validated): ?int
    {
        if ((int) $validated['is_related_to_another_issue'] !== 1) {
            return null;
        }

        return isset($validated['related_issue_id']) ? (int) $validated['related_issue_id'] : null;
    }

    private function languagesOptions()
    {
        return Language::query()->orderBy('name', 'asc')->get();
    }
}
