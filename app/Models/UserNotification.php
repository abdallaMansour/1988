<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserNotification extends Model
{
    protected $fillable = [
        'title',
        'message',
        'admin_id',
        'send_to_all',
    ];

    protected function casts(): array
    {
        return [
            'send_to_all' => 'boolean',
        ];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function recipients(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_notification_user');
    }

    public function reads(): HasMany
    {
        return $this->hasMany(UserNotificationRead::class);
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        return $query->where(function (Builder $q) use ($user) {
            $q->where('send_to_all', true)
                ->orWhereHas('recipients', fn (Builder $r) => $r->where('users.id', $user->id));
        });
    }

    public function isVisibleTo(User $user): bool
    {
        if ($this->send_to_all) {
            return true;
        }

        return $this->recipients()->where('users.id', $user->id)->exists();
    }

    public function isReadBy(User $user): bool
    {
        return $this->reads()->where('user_id', $user->id)->exists();
    }

    public function markAsReadBy(User $user): void
    {
        $this->reads()->firstOrCreate(
            ['user_id' => $user->id],
            ['read_at' => now()]
        );
    }
}
