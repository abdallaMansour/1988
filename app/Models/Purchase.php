<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Purchase extends Model
{
    protected $fillable = [
        'user_id',
        'coupon_id',
        'purchasable_type',
        'purchasable_id',
        'amount',
        'currency',
        'subtotal',
        'discount_amount',
        'status',
        'ziina_payment_intent_id',
        'ziina_operation_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function purchasable(): MorphTo
    {
        return $this->morphTo();
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
