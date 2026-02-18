<?php

namespace App\Modules\Audit\Models;

use App\Modules\Core\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxAuditor extends Model
{
    use HasFactory;

    protected $table = 'trx_auditors';

    protected $fillable = [
        'audit_id',
        'user_id',
        'role',
        'assigned_scope',
        'visit_date_start',
        'visit_date_end',
        'visit_time_start',
        'visit_time_end',
        'visit_location',
        'assigned_by',
        'assigned_at',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'visit_date_start' => 'date',
        'visit_date_end' => 'date',
        'assigned_at' => 'datetime',
    ];

    const ROLE_LEAD = 'lead';
    const ROLE_MEMBER = 'member';

    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';

    /**
     * Audit
     */
    public function audit()
    {
        return $this->belongsTo(TrxAudit::class, 'audit_id');
    }

    /**
     * Auditor user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Assigner
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Check if lead auditor
     */
    public function isLead()
    {
        return $this->role === self::ROLE_LEAD;
    }

    /**
     * Accept assignment
     */
    public function accept()
    {
        $this->status = self::STATUS_ACCEPTED;
        $this->save();
    }

    /**
     * Reject assignment
     */
    public function reject($reason)
    {
        $this->status = self::STATUS_REJECTED;
        $this->rejection_reason = $reason;
        $this->save();
    }

    /**
     * Get assigned scope as array
     */
    public function getScopeArrayAttribute()
    {
        return $this->assigned_scope ? json_decode($this->assigned_scope, true) : [];
    }

    /**
     * Check if auditor has conflict of interest
     */
    public function checkConflictOfInterest()
    {
        // Get user's primary unit
        $userUnitId = $this->user->primaryUnit();
        $auditUnitId = $this->audit->unit_id;

        // Check if auditor is from same unit or parent unit
        if ($userUnitId === $auditUnitId) {
            return true;
        }

        // Check parent relationship
        $userUnit = \App\Modules\Core\Models\RefUnit::find($userUnitId);
        if ($userUnit && ($userUnit->parent_id === $auditUnitId || $userUnit->id === $auditUnitId)) {
            return true;
        }

        return false;
    }

    /**
     * Scope pending assignments
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope accepted assignments
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }
}
