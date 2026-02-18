<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'guard_name',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    const ROLE_SUPERADMIN = 'superadmin';
    const ROLE_LPM_ADMIN = 'lpm_admin';
    const ROLE_AUDITOR = 'auditor';
    const ROLE_AUDITEE = 'auditee';
    const ROLE_RECTOR = 'rector';
    const ROLE_DEAN = 'dean';
    const ROLE_HEAD_OF_STUDY_PROGRAM = 'head_of_study_program';

    /**
     * Users with this role
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user')
            ->withPivot('unit_id', 'valid_from', 'valid_until', 'is_active')
            ->withTimestamps();
    }

    /**
     * Permissions assigned to this role
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role')
            ->withTimestamps();
    }

    /**
     * Check if role has specific permission
     */
    public function hasPermission($permissionName)
    {
        return $this->permissions()->where('name', $permissionName)->exists();
    }

    /**
     * Scope for system roles
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    /**
     * Sync permissions
     */
    public function syncPermissions(array $permissionIds)
    {
        $this->permissions()->sync($permissionIds);
    }
}
