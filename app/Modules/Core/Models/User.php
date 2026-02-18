<?php

namespace App\Modules\Core\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nip',
        'phone',
        'avatar',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Roles assigned to user
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')
            ->withPivot('unit_id', 'valid_from', 'valid_until', 'is_active')
            ->withTimestamps();
    }

    /**
     * Units where user has roles
     */
    public function units()
    {
        return $this->belongsToMany(RefUnit::class, 'role_user')
            ->withPivot('role_id', 'valid_from', 'valid_until', 'is_active')
            ->withTimestamps();
    }

    /**
     * Activity logs for this user
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Check if user has specific role
     */
    public function hasRole($roleName, $unitId = null)
    {
        $query = $this->roles()
            ->where('name', $roleName)
            ->where('role_user.is_active', true)
            ->where(function ($q) {
                $q->whereNull('role_user.valid_until')
                  ->orWhere('role_user.valid_until', '>', now());
            });

        if ($unitId) {
            $query->where('role_user.unit_id', $unitId);
        }

        return $query->exists();
    }

    /**
     * Check if user has any of the roles
     */
    public function hasAnyRole(array $roleNames, $unitId = null)
    {
        foreach ($roleNames as $roleName) {
            if ($this->hasRole($roleName, $unitId)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has specific permission (through roles)
     */
    public function hasPermission($permissionName)
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permissionName) {
                $query->where('name', $permissionName);
            })
            ->where('role_user.is_active', true)
            ->exists();
    }

    /**
     * Check if user is admin (superadmin or lpm_admin)
     */
    public function isAdmin()
    {
        return $this->hasAnyRole([Role::ROLE_SUPERADMIN, Role::ROLE_LPM_ADMIN]);
    }

    /**
     * Check if user is auditor
     */
    public function isAuditor()
    {
        return $this->hasRole(Role::ROLE_AUDITOR);
    }

    /**
     * Get primary unit for user
     */
    public function primaryUnit()
    {
        $roleUser = $this->roles()->first();
        return $roleUser ? $roleUser->pivot->unit_id : null;
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Record activity log
     */
    public function logActivity($action, $entityType = null, $entityId = null, $oldValues = null, $newValues = null, $description = null)
    {
        return ActivityLog::create([
            'user_id' => $this->id,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'description' => $description,
            'created_at' => now(),
        ]);
    }
}
