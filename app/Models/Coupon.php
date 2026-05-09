<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
