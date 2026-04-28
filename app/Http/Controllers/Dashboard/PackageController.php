<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        if (auth('admin')->check()) {
            if (!auth('admin')->user()->hasPermission('packages.view')) {
                return abort(403, 'ليس لديك صلاحية لعرض الباقات');
            }
        }
        $packages = Package::latest()->paginate(10);

        return view('dashboard.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('dashboard.packages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'yearly_price' => ['required', 'numeric', 'min:0'],
            'features' => ['nullable', 'array'],
            'features.*' => ['nullable', 'string', 'max:500'],
            'cars_count' => ['required', 'integer', 'min:0'],
            'addons_count' => ['required', 'integer', 'min:0'],
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,gif,svg,webp', 'max:2048'],
        ]);

        $package = Package::create([
            'title' => $validated['title'],
            'monthly_price' => $validated['monthly_price'],
            'yearly_price' => $validated['yearly_price'],
            'cars_count' => $validated['cars_count'],
            'addons_count' => $validated['addons_count'],
            'features' => array_filter($validated['features'] ?? []),
        ]);

        if ($request->hasFile('icon')) {
            $package->addMediaFromRequest('icon')->toMediaCollection('icon');
        }

        return redirect()->route('dashboard.packages.index')->with('success', __('Package created successfully.'));
    }

    public function edit(Package $package)
    {
        return view('dashboard.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'yearly_price' => ['required', 'numeric', 'min:0'],
            'cars_count' => ['required', 'integer', 'min:0'],
            'addons_count' => ['required', 'integer', 'min:0'],
            'features' => ['nullable', 'array'],
            'features.*' => ['nullable', 'string', 'max:500'],
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,gif,svg,webp', 'max:2048'],
        ]);

        $package->update([
            'title' => $validated['title'],
            'monthly_price' => $validated['monthly_price'],
            'yearly_price' => $validated['yearly_price'],
            'cars_count' => $validated['cars_count'],
            'addons_count' => $validated['addons_count'],
            'features' => array_filter($validated['features'] ?? []),
        ]);

        if ($request->hasFile('icon')) {
            $package->clearMediaCollection('icon');
            $package->addMediaFromRequest('icon')->toMediaCollection('icon');
        }

        return redirect()->route('dashboard.packages.index')->with('success', __('Package updated successfully.'));
    }

    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()->route('dashboard.packages.index')->with('success', __('Package deleted successfully.'));
    }
}
