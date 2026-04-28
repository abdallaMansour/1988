<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function index()
    {
        if (auth('admin')->check()) {
            if (!auth('admin')->user()->hasPermission('features.view')) {
                return abort(403, 'ليس لديك صلاحية لعرض الميزات');
            }
        }
        $features = Feature::paginate(10);

        return view('dashboard.features.index', compact('features'));
    }

    public function create()
    {
        return view('dashboard.features.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,gif,svg,webp', 'max:2048'],
        ]);

        $feature = Feature::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        if ($request->hasFile('image')) {
            $feature->addMediaFromRequest('image')->toMediaCollection('image');
        }

        return redirect()->route('dashboard.features.index')->with('success', __('تم إنشاء الميزة بنجاح.'));
    }

    public function edit(Feature $feature)
    {
        return view('dashboard.features.edit', compact('feature'));
    }

    public function update(Request $request, Feature $feature)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,gif,svg,webp', 'max:2048'],
        ]);

        $feature->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        if ($request->hasFile('image')) {
            $feature->clearMediaCollection('image');
            $feature->addMediaFromRequest('image')->toMediaCollection('image');
        }

        return redirect()->route('dashboard.features.index')->with('success', __('تم تحديث الميزة بنجاح.'));
    }

    public function destroy(Feature $feature)
    {
        $feature->delete();

        return redirect()->route('dashboard.features.index')->with('success', __('تم حذف الميزة بنجاح.'));
    }
}
