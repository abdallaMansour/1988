<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'admin_role')->withTimestamps();
    }

    public function hasRole(string $slug): bool
    {
        return $this->roles()->where('slug', $slug)->exists();
    }

    public function hasPermission(string $slug): bool
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($slug)) {
                return true;
            }
        }
        return false;
    }

    public function canAccessAdminManagement(): bool
    {
        if ($this->roles()->count() === 0) {
            return true;
        }
        return $this->hasRole('super_admin')
            || $this->hasPermission('admins.view')
            || $this->hasPermission('roles.view')
            || $this->hasPermission('permissions.view');
    }

    /**
     * Check if admin can access a permission (super_admin or roles empty = full access).
     */
    public function canAccess(string $permission): bool
    {
        if ($this->roles()->count() === 0) {
            return true;
        }
        return $this->hasRole('super_admin') || $this->hasPermission($permission);
    }
}
