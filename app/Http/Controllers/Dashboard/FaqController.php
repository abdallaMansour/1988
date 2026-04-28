<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        if (auth('admin')->check()) {
            if (!auth('admin')->user()->hasPermission('faq.view')) {
                return abort(403, 'ليس لديك صلاحية لعرض الأسئلة الشائعة');
            }
        }
        $faqs = Faq::paginate(10);

        return view('dashboard.faq.index', compact('faqs'));
    }

    public function create()
    {
        return view('dashboard.faq.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:500'],
            'answer' => ['required', 'string'],
        ]);

        Faq::create([
            'question' => $validated['question'],
            'answer' => $validated['answer'],
        ]);

        return redirect()->route('dashboard.faq.index')->with('success', __('تم إنشاء السؤال بنجاح.'));
    }

    public function edit(Faq $faq)
    {
        return view('dashboard.faq.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:500'],
            'answer' => ['required', 'string'],
        ]);

        $faq->update($validated);

        return redirect()->route('dashboard.faq.index')->with('success', __('تم تحديث السؤال بنجاح.'));
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('dashboard.faq.index')->with('success', __('تم حذف السؤال بنجاح.'));
    }
}
