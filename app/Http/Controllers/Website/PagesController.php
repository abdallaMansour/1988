<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\Issue;
use App\Models\Package;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Rating;
use App\Models\SiteSetting;

class PagesController extends Controller
{
    public function landingPage()
    {
        $packages = Package::orderBy('monthly_price', 'asc')->get();
        $faqs = Faq::orderBy('id', 'asc')->get();
        $features = Feature::orderBy('id', 'asc')->get();
        $landingIssues = Issue::query()
            ->where('is_active', true)
            ->orderByDesc('id')
            ->limit(6)
            ->get();
        $landingProducts = Product::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->limit(6)
            ->get();
        $landingRatings = Rating::query()
            ->latest('created_at')
            ->get();

        return view('website.landing-page', compact('packages', 'faqs', 'features', 'landingIssues', 'landingProducts', 'landingRatings'));
    }

    public function aboutUs()
    {
        $settings = SiteSetting::singleton();

        return view('website.pages.about-us', compact('settings'));
    }

    public function aboutNovel()
    {
        $settings = SiteSetting::singleton();

        return view('website.pages.about-novel', compact('settings'));
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

    public function howToPlay()
    {
        $settings = SiteSetting::singleton();

        return view('website.pages.how-to-play', compact('settings'));
    }

    public function returnReplacementPolicy()
    {
        $settings = SiteSetting::singleton();

        return view('website.pages.return-replacement-policy', compact('settings'));
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

        $ownsIssue = auth('web')->check()
            && auth()->user()->hasPaidPurchaseFor($issue);

        if ($ownsIssue) {
            $issue->load([
                'hints' => fn ($q) => $q->orderBy('sort_order')->orderBy('id'),
            ]);
        }

        $hintsExist = $ownsIssue
            ? $issue->hints->isNotEmpty()
            : $issue->hints()->exists();

        $hasPremiumAssets = $issue->hasMedia('story_video')
            || $issue->hasMedia('ending_video')
            || $issue->getMedia('evidence')->isNotEmpty()
            || $hintsExist;

        return view('website.pages.issue-show', compact('issue', 'ownsIssue', 'hasPremiumAssets'));
    }

    public function myPurchases()
    {
        $purchases = Purchase::query()
            ->where('user_id', auth()->id())
            ->where('status', 'paid')
            ->where(function ($q) {
                $q->whereNull('gift_claim_token')
                    ->orWhereNotNull('gift_from_user_id');
            })
            ->whereIn('purchasable_type', [Issue::class, Product::class])
            ->with(['purchasable', 'giftFrom'])
            ->latest()
            ->paginate(12);

        return view('website.pages.my-purchases', compact('purchases'));
    }
}
