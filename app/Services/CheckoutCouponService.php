<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Issue;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
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

    /**
     * @param  Collection<int, array{key: string, line_subtotal: float, model: Model}>  $cartLines
     * @return array{
     *     ok: true,
     *     coupon: ?Coupon,
     *     subtotal: float,
     *     eligible_subtotal: float,
     *     discount_amount: float,
     *     final_amount: float,
     *     line_allocations: array<string, array{line_subtotal: float, discount_amount: float, final_amount: float}>
     * }|array{ok: false, message: string}
     */
    public function applyToCart(?string $couponCode, Collection $cartLines, ?int $userId): array
    {
        $subtotal = round((float) $cartLines->sum('line_subtotal'), 2);
        $lineAllocations = [];

        foreach ($cartLines as $line) {
            $lineAllocations[$line['key']] = [
                'line_subtotal' => (float) $line['line_subtotal'],
                'discount_amount' => 0.0,
                'final_amount' => (float) $line['line_subtotal'],
            ];
        }

        $code = trim((string) ($couponCode ?? ''));

        if ($code === '') {
            return [
                'ok' => true,
                'coupon' => null,
                'subtotal' => $subtotal,
                'eligible_subtotal' => $subtotal,
                'discount_amount' => 0.0,
                'final_amount' => $subtotal,
                'line_allocations' => $lineAllocations,
            ];
        }

        if ($userId === null) {
            return ['ok' => false, 'message' => 'يجب تسجيل الدخول لاستخدام الكوبون.'];
        }

        if ($cartLines->isEmpty()) {
            return ['ok' => false, 'message' => 'السلة فارغة.'];
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

        if (! $coupon->hasRemainingGlobalUses()) {
            return ['ok' => false, 'message' => 'تم استنفاد عدد مرات استخدام هذا الكوبون.'];
        }

        if (! $coupon->hasRemainingUsesForUser($userId)) {
            return ['ok' => false, 'message' => 'لقد استخدمت هذا الكوبون الحد الأقصى المسموح لك.'];
        }

        $eligibleSubtotal = 0.0;
        foreach ($cartLines as $line) {
            if ($coupon->appliesToPurchasable($line['model'])) {
                $eligibleSubtotal += (float) $line['line_subtotal'];
            }
        }
        $eligibleSubtotal = round($eligibleSubtotal, 2);

        if ($eligibleSubtotal <= 0) {
            return ['ok' => false, 'message' => 'لا يمكن استخدام هذا الكوبون لأي عنصر في السلة.'];
        }

        $discount = round(min($this->computeDiscount($coupon, $eligibleSubtotal), $eligibleSubtotal), 2);
        $final = round(max($subtotal - $discount, 0), 2);

        if ($final <= 0) {
            return ['ok' => false, 'message' => 'لا يمكن إتمام الدفع لأن المبلغ النهائي صفر بعد الخصم.'];
        }

        $amountInMinorUnits = (int) round($final * 100);
        if ($amountInMinorUnits < 200) {
            return ['ok' => false, 'message' => 'المبلغ بعد الخصم أقل من الحد الأدنى للدفع عبر زينه.'];
        }

        $remainingDiscount = $discount;
        $eligibleLines = $cartLines->filter(fn ($line) => $coupon->appliesToPurchasable($line['model']))->values();
        $eligibleCount = $eligibleLines->count();

        foreach ($eligibleLines as $index => $line) {
            $lineSubtotal = (float) $line['line_subtotal'];
            if ($index === $eligibleCount - 1) {
                $lineDiscount = round($remainingDiscount, 2);
            } else {
                $lineDiscount = round($discount * ($lineSubtotal / $eligibleSubtotal), 2);
                $remainingDiscount -= $lineDiscount;
            }
            $lineDiscount = min($lineDiscount, $lineSubtotal);
            $lineAllocations[$line['key']] = [
                'line_subtotal' => $lineSubtotal,
                'discount_amount' => $lineDiscount,
                'final_amount' => round($lineSubtotal - $lineDiscount, 2),
            ];
        }

        return [
            'ok' => true,
            'coupon' => $coupon,
            'subtotal' => $subtotal,
            'eligible_subtotal' => $eligibleSubtotal,
            'discount_amount' => $discount,
            'final_amount' => $final,
            'line_allocations' => $lineAllocations,
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
