<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\Issue;
use App\Models\Package;
use App\Models\Product;
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

    public function products()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->paginate(12);

        return view('website.pages.products', compact('products'));
    }

    public function product(Product $product)
    {
        abort_unless($product->is_active, 404);

        return view('website.pages.product-show', compact('product'));
    }

    public function issues()
    {
        $issues = Issue::query()
            ->where('is_active', true)
            ->orderBy('title')
            ->paginate(12);

        return view('website.pages.issues', compact('issues'));
    }

    public function issue(Issue $issue)
    {
        abort_unless($issue->is_active, 404);

        $issue->loadMissing('relatedIssue');

        return view('website.pages.issue-show', compact('issue'));
    }
}
