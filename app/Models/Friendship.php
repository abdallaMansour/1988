<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Friendship extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_ACCEPTED = 'accepted';

    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'requester_id',
        'addressee_id',
        'status',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function addressee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'addressee_id');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeAccepted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }

    public function scopeBetweenUsers(Builder $query, int $userA, int $userB): Builder
    {
        return $query->where(function (Builder $q) use ($userA, $userB) {
            $q->where(function (Builder $inner) use ($userA, $userB) {
                $inner->where('requester_id', $userA)->where('addressee_id', $userB);
            })->orWhere(function (Builder $inner) use ($userA, $userB) {
                $inner->where('requester_id', $userB)->where('addressee_id', $userA);
            });
        });
    }

    public function involvesUser(int $userId): bool
    {
        return $this->requester_id === $userId || $this->addressee_id === $userId;
    }

    public function otherUser(int $userId): ?User
    {
        if ($this->requester_id === $userId) {
            return $this->addressee;
        }
        if ($this->addressee_id === $userId) {
            return $this->requester;
        }

        return null;
    }
}
