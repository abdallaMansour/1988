<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    public const DISCOUNT_PERCENT = 'percent';

    public const DISCOUNT_FIXED = 'fixed';

    public const APPLIES_ALL = 'all';

    public const APPLIES_PRODUCTS = 'products';

    public const APPLIES_ISSUES = 'issues';

    public const APPLIES_SPECIFIC_PRODUCTS = 'specific_products';

    public const APPLIES_SPECIFIC_ISSUES = 'specific_issues';

    protected $fillable = [
        'name',
        'code',
        'discount_type',
        'discount_value',
        'total_usage_limit',
        'per_user_usage_limit',
        'starts_at',
        'ends_at',
        'applies_to',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'total_usage_limit' => 'integer',
            'per_user_usage_limit' => 'integer',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function issues(): BelongsToMany
    {
        return $this->belongsToMany(Issue::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function appliesToPurchasable(Model $model): bool
    {
        return match ($this->applies_to) {
            self::APPLIES_ALL => true,
            self::APPLIES_PRODUCTS => $model instanceof Product,
            self::APPLIES_ISSUES => $model instanceof Issue,
            self::APPLIES_SPECIFIC_PRODUCTS => $model instanceof Product
                && $this->products()->where('products.id', $model->getKey())->exists(),
            self::APPLIES_SPECIFIC_ISSUES => $model instanceof Issue
                && $this->issues()->where('issues.id', $model->getKey())->exists(),
            default => false,
        };
    }

    public function isCurrentlyActive(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $now = now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->ends_at && $now->gt($this->ends_at)) {
            return false;
        }

        return true;
    }

    public function hasRemainingGlobalUses(): bool
    {
        if ($this->total_usage_limit === null) {
            return true;
        }

        $used = Purchase::query()
            ->where('coupon_id', $this->id)
            ->where('status', 'paid')
            ->count();

        return $used < $this->total_usage_limit;
    }

    public function hasRemainingUsesForUser(int $userId): bool
    {
        if ($this->per_user_usage_limit === null) {
            return true;
        }

        $used = Purchase::query()
            ->where('coupon_id', $this->id)
            ->where('user_id', $userId)
            ->where('status', 'paid')
            ->count();

        return $used < $this->per_user_usage_limit;
    }

    public static function appliesToLabels(): array
    {
        return [
            self::APPLIES_ALL => 'الجميع',
            self::APPLIES_PRODUCTS => 'المنتجات',
            self::APPLIES_ISSUES => 'القضايا',
            self::APPLIES_SPECIFIC_PRODUCTS => 'منتجات محددة',
            self::APPLIES_SPECIFIC_ISSUES => 'قضايا محددة',
        ];
    }

    public static function discountTypeLabels(): array
    {
        return [
            self::DISCOUNT_PERCENT => 'نسبة مئوية',
            self::DISCOUNT_FIXED => 'مبلغ ثابت',
        ];
    }
}
