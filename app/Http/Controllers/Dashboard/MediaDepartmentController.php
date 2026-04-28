<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\MediaDepartment;
use Illuminate\Http\Request;

class MediaDepartmentController extends Controller
{
    public function index()
    {
        $media = MediaDepartment::get();

        return view('dashboard.media-department.index', compact('media'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'login_image' => ['nullable', 'image', 'mimes:jpeg,png,gif,svg,webp', 'max:2048'],
            'register_image' => ['nullable', 'image', 'mimes:jpeg,png,gif,svg,webp', 'max:2048'],
            'dashboard_banner' => ['nullable', 'image', 'mimes:jpeg,png,gif,svg,webp', 'max:2048'],
        ]);

        $media = MediaDepartment::get();

        if ($request->hasFile('login_image')) {
            $media->clearMediaCollection('login_image');
            $media->addMediaFromRequest('login_image')->toMediaCollection('login_image');
        }
        if ($request->hasFile('register_image')) {
            $media->clearMediaCollection('register_image');
            $media->addMediaFromRequest('register_image')->toMediaCollection('register_image');
        }
        if ($request->hasFile('dashboard_banner')) {
            $media->clearMediaCollection('dashboard_banner');
            $media->addMediaFromRequest('dashboard_banner')->toMediaCollection('dashboard_banner');
        }

        return redirect()->route('dashboard.media-department.index')->with('success', __('تم تحديث الصور بنجاح.'));
    }
}
