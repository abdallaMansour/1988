<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LanguageController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Admin|null $admin */
        $admin = auth('admin')->user();
        if (! $admin) {
            abort(403);
        }
        if ($admin->roles()->count() > 0 && ! $admin->hasRole('super_admin')) {
            if (! $admin->hasPermission('languages.view') && ! $admin->hasPermission('languages.manage')) {
                abort(403, 'ليس لديك صلاحية لعرض اللغات');
            }
        }

        $languages = Language::query()->orderBy('name', 'asc')->paginate(15);

        return view('dashboard.languages.index', compact('languages'));
    }

    public function create()
    {
        $this->ensureManage();

        return view('dashboard.languages.create');
    }

    public function store(Request $request)
    {
        $this->ensureManage();

        $request->merge(['code' => strtolower((string) $request->input('code'))]);
        $validated = $request->validate($this->rules());

        Language::create($validated);

        return redirect()->route('dashboard.languages.index')->with('success', 'تم إضافة اللغة بنجاح.');
    }

    public function edit(Language $language)
    {
        $this->ensureManage();

        return view('dashboard.languages.edit', compact('language'));
    }

    public function update(Request $request, Language $language)
    {
        $this->ensureManage();

        $request->merge(['code' => strtolower((string) $request->input('code'))]);
        $validated = $request->validate($this->rules($language));

        $language->update($validated);

        return redirect()->route('dashboard.languages.index')->with('success', 'تم تحديث اللغة بنجاح.');
    }

    public function destroy(Language $language)
    {
        $this->ensureManage();

        if (Issue::query()->whereJsonContains('languages', $language->code, 'and', false)->exists()) {
            return redirect()
                ->route('dashboard.languages.index')
                ->withErrors(['language' => 'لا يمكن حذف لغة مستخدمة في القضايا.']);
        }

        Language::destroy($language->id);

        return redirect()->route('dashboard.languages.index')->with('success', 'تم حذف اللغة بنجاح.');
    }

    private function rules(?Language $language = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:10',
                'regex:/^[a-zA-Z_-]+$/',
                Rule::unique('languages', 'code')->ignore($language?->id),
            ],
            'english_name' => ['required', 'string', 'max:255'],
        ];
    }

    private function ensureManage(): void
    {
        /** @var \App\Models\Admin|null $admin */
        $admin = auth('admin')->user();
        if (! $admin) {
            abort(403);
        }
        if ($admin->roles()->count() > 0 && ! $admin->hasRole('super_admin')) {
            abort_unless($admin->hasPermission('languages.manage'), 403, 'ليس لديك صلاحية لإدارة اللغات');
        }
    }
}
