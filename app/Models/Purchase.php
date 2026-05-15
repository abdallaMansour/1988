<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Purchase extends Model
{
    protected $fillable = [
        'user_id',
        'checkout_batch_id',
        'coupon_id',
        'purchasable_type',
        'purchasable_id',
        'quantity',
        'amount',
        'currency',
        'subtotal',
        'discount_amount',
        'status',
        'ziina_payment_intent_id',
        'ziina_operation_id',
        'gift_claim_token',
        'gift_invite_email',
        'gift_from_user_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'quantity' => 'integer',
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

    public function giftFrom(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gift_from_user_id');
    }

    public function purchasable(): MorphTo
    {
        return $this->morphTo();
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isPendingGift(): bool
    {
        return $this->gift_claim_token !== null && $this->gift_from_user_id === null;
    }

    public function isReceivedGift(): bool
    {
        return $this->gift_from_user_id !== null;
    }
}
