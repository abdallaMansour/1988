<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\Package;
use App\Models\SiteSetting;

class PagesController extends Controller
{
    public function landingPage()
    {
        $packages = Package::orderBy('monthly_price', 'asc')->get();
        $faqs = Faq::orderBy('id', 'asc')->get();
        $features = Feature::orderBy('id', 'asc')->get();

        return view('website.landing-page', compact('packages', 'faqs', 'features'));
    }

    public function privacyPolicy()
    {
        $settings = SiteSetting::singleton();

        return view('website.pages.privacy-policy', compact('settings'));
    }

    public function termsAndConditions()
    {
        $settings = SiteSetting::singleton();

        return view('website.pages.terms-and-conditions', compact('settings'));
    }

    public function faq()
    {
        $faqs = Faq::orderBy('order', 'asc')->orderBy('id', 'asc')->get();

        return view('website.pages.faq', compact('faqs'));
    }

    public function features()
    {
        $features = Feature::orderBy('order', 'asc')->orderBy('id', 'asc')->get();

        return view('website.pages.features', compact('features'));
    }
}
