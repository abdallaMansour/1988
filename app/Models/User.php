<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    public const ACCOUNT_PUBLIC = 'public';

    public const ACCOUNT_PRIVATE = 'private';

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'investigator_name',
        'account_type',
        'country',
        'profile_avatar_id',
        'email',
        'email_verified_at',
        'password',
        'banned_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'banned_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isBanned(): bool
    {
        return $this->banned_at !== null;
    }

    public function isFullyVerified(): bool
    {
        return true;
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function profileAvatar()
    {
        return $this->belongsTo(ProfileAvatar::class);
    }

    public function friendshipsSent()
    {
        return $this->hasMany(Friendship::class, 'requester_id');
    }

    public function friendshipsReceived()
    {
        return $this->hasMany(Friendship::class, 'addressee_id');
    }

    public function friendIds(): array
    {
        $sent = Friendship::query()
            ->accepted()
            ->where('requester_id', $this->id)
            ->pluck('addressee_id');

        $received = Friendship::query()
            ->accepted()
            ->where('addressee_id', $this->id)
            ->pluck('requester_id');

        return $sent->merge($received)->unique()->values()->all();
    }

    public function friendshipWith(User $other): ?Friendship
    {
        return Friendship::query()
            ->betweenUsers($this->id, $other->id)
            ->first();
    }

    public function isFriendWith(User $other): bool
    {
        return Friendship::query()
            ->accepted()
            ->betweenUsers($this->id, $other->id)
            ->exists();
    }

    public function avatarUrl(): ?string
    {
        return $this->profileAvatar?->getFirstMediaUrl('image') ?: null;
    }

    public function isPrivateAccount(): bool
    {
        return $this->account_type === self::ACCOUNT_PRIVATE;
    }

    public function isPrivateTo(?User $viewer): bool
    {
        if (! $this->isPrivateAccount()) {
            return false;
        }

        if (! $viewer || $viewer->id === $this->id) {
            return false;
        }

        return ! $viewer->isFriendWith($this);
    }

    public function displayInvestigatorNameFor(?User $viewer): string
    {
        if ($this->isPrivateTo($viewer)) {
            return 'XXX';
        }

        return (string) $this->investigator_name;
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function hasPaidPurchaseFor(Issue $issue): bool
    {
        return $this->purchases()
            ->where('status', 'paid')
            ->where('purchasable_type', $issue->getMorphClass())
            ->where('purchasable_id', $issue->getKey())
            ->exists();
    }

    public function getActiveSubscriptionAttribute()
    {
        return $this->subscriptions()->active()->latest('expires_at')->first();
    }
}
