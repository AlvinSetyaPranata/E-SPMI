<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ref_units';

    // Unit Type Constants
    const TYPE_UNIVERSITY = 'university';
    const TYPE_FACULTY = 'faculty';
    const TYPE_DEPARTMENT = 'department';
    const TYPE_PROGRAM_STUDY = 'program_study';
    const TYPE_BUREAU = 'bureau';
    const TYPE_LPM = 'lpm';
    const TYPE_OTHER = 'other';

    protected $fillable = [
        'code',
        'name',
        'type',
        'parent_id',
        'description',
        'head_name',
        'head_nip',
        'contact_email',
        'contact_phone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Parent unit (for hierarchy)
     */
    public function parent()
    {
        return $this->belongsTo(RefUnit::class, 'parent_id');
    }

    /**
     * Children units
     */
    public function children()
    {
        return $this->hasMany(RefUnit::class, 'parent_id');
    }

    /**
     * Get all descendants recursively
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Users with roles in this unit
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user')
            ->withPivot('role_id', 'valid_from', 'valid_until', 'is_active')
            ->withTimestamps();
    }

    /**
     * Scope for active units
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get root units (university level)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get all unit types as array
     */
    public static function getTypes()
    {
        return [
            self::TYPE_UNIVERSITY => 'Universitas',
            self::TYPE_FACULTY => 'Fakultas',
            self::TYPE_DEPARTMENT => 'Jurusan',
            self::TYPE_PROGRAM_STUDY => 'Program Studi',
            self::TYPE_BUREAU => 'Biro',
            self::TYPE_LPM => 'LPM',
            self::TYPE_OTHER => 'Lainnya',
        ];
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute()
    {
        return self::getTypes()[$this->type] ?? $this->type;
    }
}
