<?php

namespace App\Modules\Pengendalian\Models;

use App\Modules\Audit\Models\TrxAudit;
use App\Modules\Audit\Models\TrxAuditDetail;
use App\Modules\Core\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrxFinding extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trx_findings';

    protected $fillable = [
        'ptk_code',
        'audit_id',
        'audit_detail_id',
        'type',
        'category',
        'finding_description',
        'reference_requirement',
        'objective_evidence',
        'identified_by',
        'identified_at',
        'root_cause_analysis',
        'corrective_action_plan',
        'preventive_action_plan',
        'submitted_by',
        'submitted_at',
        'due_date',
        'status',
        'verification_note',
        'verified_by',
        'verified_at',
        'attachment_files',
        'is_escalated',
        'escalated_at',
        'escalated_to',
    ];

    protected $casts = [
        'identified_at' => 'datetime',
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
        'escalated_at' => 'datetime',
        'due_date' => 'date',
        'attachment_files' => 'json',
        'is_escalated' => 'boolean',
    ];

    const TYPE_OBSERVATION = 'observation';
    const TYPE_MINOR_NC = 'minor_nc';
    const TYPE_MAJOR_NC = 'major_nc';

    const CATEGORY_DOCUMENTATION = 'documentation';
    const CATEGORY_IMPLEMENTATION = 'implementation';
    const CATEGORY_SYSTEM = 'system';
    const CATEGORY_RESOURCE = 'resource';

    const STATUS_OPEN = 'open';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_WAITING_VERIFICATION = 'waiting_verification';
    const STATUS_CLOSED = 'closed';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_ESCALATED = 'escalated';

    /**
     * Generate unique PTK code
     */
    public static function generateCode()
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'PTK-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Audit
     */
    public function audit()
    {
        return $this->belongsTo(TrxAudit::class, 'audit_id');
    }

    /**
     * Audit detail
     */
    public function auditDetail()
    {
        return $this->belongsTo(TrxAuditDetail::class, 'audit_detail_id');
    }

    /**
     * Identifier (auditor)
     */
    public function identifiedBy()
    {
        return $this->belongsTo(User::class, 'identified_by');
    }

    /**
     * Submitter (auditee)
     */
    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * Verifier
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Escalated to user
     */
    public function escalatedTo()
    {
        return $this->belongsTo(User::class, 'escalated_to');
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute()
    {
        $labels = [
            self::TYPE_OBSERVATION => 'Observasi (OB)',
            self::TYPE_MINOR_NC => 'KTS Minor',
            self::TYPE_MAJOR_NC => 'KTS Mayor',
        ];
        return $labels[$this->type] ?? $this->type;
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_OPEN => 'Terbuka',
            self::STATUS_IN_PROGRESS => 'Dalam Proses',
            self::STATUS_WAITING_VERIFICATION => 'Menunggu Verifikasi',
            self::STATUS_CLOSED => 'Tertutup',
            self::STATUS_OVERDUE => 'Terlambat',
            self::STATUS_ESCALATED => 'Eskalasi',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Check if overdue
     */
    public function checkOverdue()
    {
        if ($this->status !== self::STATUS_CLOSED && $this->due_date < now()->toDateString()) {
            $this->status = self::STATUS_OVERDUE;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Submit root cause and action plans
     */
    public function submitResponse($rootCause, $correctiveAction, $preventiveAction, $userId, $attachments = null)
    {
        $this->root_cause_analysis = $rootCause;
        $this->corrective_action_plan = $correctiveAction;
        $this->preventive_action_plan = $preventiveAction;
        $this->submitted_by = $userId;
        $this->submitted_at = now();
        $this->status = self::STATUS_WAITING_VERIFICATION;
        
        if ($attachments) {
            $this->attachment_files = $attachments;
        }
        
        $this->save();
    }

    /**
     * Verify and close
     */
    public function verify($verified, $note, $userId)
    {
        if ($verified) {
            $this->status = self::STATUS_CLOSED;
        } else {
            $this->status = self::STATUS_IN_PROGRESS;
        }
        
        $this->verification_note = $note;
        $this->verified_by = $userId;
        $this->verified_at = now();
        $this->save();
    }

    /**
     * Escalate finding
     */
    public function escalate($escalatedTo, $reason = null)
    {
        $this->is_escalated = true;
        $this->escalated_to = $escalatedTo;
        $this->escalated_at = now();
        $this->status = self::STATUS_ESCALATED;
        
        if ($reason) {
            $this->verification_note = ($this->verification_note ? $this->verification_note . "\n" : '') . "Eskalasi: " . $reason;
        }
        
        $this->save();
    }

    /**
     * Get remaining days
     */
    public function getRemainingDaysAttribute()
    {
        if ($this->status === self::STATUS_CLOSED) {
            return 0;
        }
        return now()->diffInDays($this->due_date, false);
    }

    /**
     * Scope overdue findings
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', self::STATUS_CLOSED)
            ->where('due_date', '<', now()->toDateString());
    }

    /**
     * Scope by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
