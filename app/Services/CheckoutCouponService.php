<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Issue;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CheckoutCouponService
{
    /**
     * @return array{ok: true, coupon: ?Coupon, subtotal: float, discount_amount: float, final_amount: float}|array{ok: false, message: string}
     */
    public function apply(?string $couponCode, Model $purchasable, ?int $userId): array
    {
        $subtotal = round($this->baseAmount($purchasable), 2);
        $code = trim((string) ($couponCode ?? ''));

        if ($code === '') {
            return [
                'ok' => true,
                'coupon' => null,
                'subtotal' => $subtotal,
                'discount_amount' => 0.0,
                'final_amount' => $subtotal,
            ];
        }

        if ($userId === null) {
            return ['ok' => false, 'message' => 'يجب تسجيل الدخول لاستخدام الكوبون.'];
        }

        $coupon = Coupon::query()
            ->whereRaw('LOWER(TRIM(code)) = ?', [Str::lower($code)])
            ->first();

        if (! $coupon) {
            return ['ok' => false, 'message' => 'كود الكوبون غير صحيح.'];
        }

        if (! $coupon->isCurrentlyActive()) {
            return ['ok' => false, 'message' => 'هذا الكوبون غير نشط أو انتهت صلاحيته.'];
        }

        if (! $coupon->appliesToPurchasable($purchasable)) {
            return ['ok' => false, 'message' => 'لا يمكن استخدام هذا الكوبون لهذا العنصر.'];
        }

        if (! $coupon->hasRemainingGlobalUses()) {
            return ['ok' => false, 'message' => 'تم استنفاد عدد مرات استخدام هذا الكوبون.'];
        }

        if (! $coupon->hasRemainingUsesForUser($userId)) {
            return ['ok' => false, 'message' => 'لقد استخدمت هذا الكوبون الحد الأقصى المسموح لك.'];
        }

        $discount = round(min($this->computeDiscount($coupon, $subtotal), $subtotal), 2);
        $final = round(max($subtotal - $discount, 0), 2);

        if ($final <= 0) {
            return ['ok' => false, 'message' => 'لا يمكن إتمام الدفع لأن المبلغ النهائي صفر بعد الخصم.'];
        }

        $amountInMinorUnits = (int) round($final * 100);
        if ($amountInMinorUnits < 200) {
            return ['ok' => false, 'message' => 'المبلغ بعد الخصم أقل من الحد الأدنى للدفع عبر زينه.'];
        }

        return [
            'ok' => true,
            'coupon' => $coupon,
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'final_amount' => $final,
        ];
    }

    private function baseAmount(Model $purchasable): float
    {
        return match (true) {
            $purchasable instanceof Product => (float) $purchasable->sale_price_after_discount,
            $purchasable instanceof Issue => (float) $purchasable->purchase_price_after_discount,
            default => throw new \InvalidArgumentException('Unsupported purchasable type.'),
        };
    }

    private function computeDiscount(Coupon $coupon, float $subtotal): float
    {
        if ($coupon->discount_type === Coupon::DISCOUNT_PERCENT) {
            $pct = (float) $coupon->discount_value;
            if ($pct <= 0) {
                return 0.0;
            }
            if ($pct > 100) {
                $pct = 100;
            }

            return round($subtotal * ($pct / 100), 2);
        }

        $fixed = (float) $coupon->discount_value;

        return $fixed > 0 ? min($fixed, $subtotal) : 0.0;
    }
}
