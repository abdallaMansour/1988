<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function privacyPolicy()
    {
        $settings = SiteSetting::singleton();

        return view('dashboard.site-settings.privacy-policy', compact('settings'));
    }

    public function updatePrivacyPolicy(Request $request)
    {
        $validated = $request->validate([
            'privacy_policy' => ['nullable', 'string'],
        ]);

        $settings = SiteSetting::singleton();
        $settings->update(['privacy_policy' => $validated['privacy_policy'] ?? '']);

        return redirect()->route('dashboard.privacy-policy.index')->with('success', __('تم تحديث سياسة الخصوصية بنجاح.'));
    }

    public function termsAndConditions()
    {
        $settings = SiteSetting::singleton();

        return view('dashboard.site-settings.terms-and-conditions', compact('settings'));
    }

    public function updateTermsAndConditions(Request $request)
    {
        $validated = $request->validate([
            'terms_and_conditions' => ['nullable', 'string'],
        ]);

        $settings = SiteSetting::singleton();
        $settings->update(['terms_and_conditions' => $validated['terms_and_conditions'] ?? '']);

        return redirect()->route('dashboard.terms-and-conditions.index')->with('success', __('تم تحديث الشروط والأحكام بنجاح.'));
    }

    public function aboutUs()
    {
        $settings = SiteSetting::singleton();

        return view('dashboard.site-settings.about-us', compact('settings'));
    }

    public function updateAboutUs(Request $request)
    {
        $validated = $request->validate([
            'about_us' => ['nullable', 'string'],
        ]);

        $settings = SiteSetting::singleton();
        $settings->update(['about_us' => $validated['about_us'] ?? '']);

        return redirect()->route('dashboard.about-us.index')->with('success', __('تم تحديث المعلومات العامة بنجاح.'));
    }

    public function iosAndAndroidAppLink()
    {
        $settings = SiteSetting::singleton();

        return view('dashboard.site-settings.ios-and-android-app-link', compact('settings'));
    }

    public function updateIosAndAndroidAppLink(Request $request)
    {
        $validated = $request->validate([
            'ios_app_link' => ['nullable', 'string'],
            'android_app_link' => ['nullable', 'string'],
        ]);

        $settings = SiteSetting::singleton();
        $settings->update(['ios_app_link' => $validated['ios_app_link'] ?? '', 'android_app_link' => $validated['android_app_link'] ?? '']);

        return redirect()->route('dashboard.ios-and-android-app-link.index')->with('success', __('تم تحديث روابط التطبيقات بنجاح.'));
    }

    public function aboutNovel()
    {
        $settings = SiteSetting::singleton();

        return view('dashboard.site-settings.about-novel', compact('settings'));
    }

    public function updateAboutNovel(Request $request)
    {
        $validated = $request->validate([
            'novel_title' => ['nullable', 'string', 'max:255'],
            'novel_description' => ['nullable', 'string'],
            'novel_image' => ['nullable', 'image', 'mimes:jpeg,png,gif,svg,webp'],
        ]);

        $settings = SiteSetting::singleton();
        $settings->update([
            'novel_title' => $validated['novel_title'] ?? '',
            'novel_description' => $validated['novel_description'] ?? '',
        ]);

        if ($request->hasFile('novel_image')) {
            $settings->clearMediaCollection('novel_image');
            $settings->addMediaFromRequest('novel_image')->toMediaCollection('novel_image');
        }

        return redirect()->route('dashboard.about-novel.index')->with('success', __('تم تحديث بيانات الرواية بنجاح.'));
    }
}
