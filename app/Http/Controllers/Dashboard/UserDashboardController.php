<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\Product;
use App\Models\Purchase;

class UserDashboardController extends Controller
{
    public function crimesFile()
    {
        return $this->placeholder('ملف الجرائم');
    }

    public function purchases()
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
            ->paginate(15);

        return view('dashboard.user.purchases', compact('purchases'));
    }

    public function friends()
    {
        return $this->placeholder('اصدقائي');
    }

    public function notifications()
    {
        return $this->placeholder('الإشعارات');
    }

    public function profile()
    {
        return $this->placeholder('الملف الشخصي');
    }

    private function placeholder(string $title)
    {
        return view('dashboard.user.placeholder', [
            'title' => $title,
            'message' => 'لا توجد بيانات حالياً — قريباً',
        ]);
    }
}
