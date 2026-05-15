<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
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
