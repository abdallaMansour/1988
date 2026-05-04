<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AvailabilityPlace;
use Illuminate\Http\Request;

class AvailabilityPlaceController extends Controller
{
    public function index()
    {
        if (auth('admin')->check()) {
            if (! auth('admin')->user()->hasPermission('availability-places.view')) {
                return abort(403, 'ليس لديك صلاحية لعرض أماكن التوفر');
            }
        }
        $availabilityPlaces = AvailabilityPlace::orderBy('id', 'desc')->paginate(10);

        return view('dashboard.availability-places.index', compact('availabilityPlaces'));
    }

    public function create()
    {
        return view('dashboard.availability-places.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'size:2', 'regex:/^[A-Za-z]{2}$/'],
            'url' => ['required', 'string', 'url'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,gif,svg,webp'],
        ]);

        $place = AvailabilityPlace::create([
            'title' => $validated['title'],
            'country' => strtoupper($validated['country']),
            'url' => $validated['url'],
            'description' => $validated['description'],
        ]);

        if ($request->hasFile('image')) {
            $place->addMediaFromRequest('image')->toMediaCollection('image');
        }

        return redirect()->route('dashboard.availability-places.index')->with('success', __('تم إنشاء مكان التوفر بنجاح.'));
    }

    public function edit(AvailabilityPlace $availability_place)
    {
        return view('dashboard.availability-places.edit', ['availabilityPlace' => $availability_place]);
    }

    public function update(Request $request, AvailabilityPlace $availability_place)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'size:2', 'regex:/^[A-Za-z]{2}$/'],
            'url' => ['required', 'string', 'url'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,gif,svg,webp'],
        ]);

        $availability_place->update([
            'title' => $validated['title'],
            'country' => strtoupper($validated['country']),
            'url' => $validated['url'],
            'description' => $validated['description'],
        ]);

        if ($request->hasFile('image')) {
            $availability_place->clearMediaCollection('image');
            $availability_place->addMediaFromRequest('image')->toMediaCollection('image');
        }

        return redirect()->route('dashboard.availability-places.index')->with('success', __('تم تحديث مكان التوفر بنجاح.'));
    }

    public function destroy(AvailabilityPlace $availability_place)
    {
        $availability_place->delete();

        return redirect()->route('dashboard.availability-places.index')->with('success', __('تم حذف مكان التوفر بنجاح.'));
    }
}
