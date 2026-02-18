<?php

namespace App\Modules\Audit\Models;

use App\Modules\Core\Models\User;
use App\Modules\Pelaksanaan\Models\TrxEvidence;
use App\Modules\Pengendalian\Models\TrxFinding;
use App\Modules\Standar\Models\MstMetric;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxAuditDetail extends Model
{
    use HasFactory;

    protected $table = 'trx_audit_details';

    protected $fillable = [
        'audit_id',
        'metric_id',
        'self_assessment_value',
        'audit_value',
        'score',
        'finding_status',
        'self_assessment_note',
        'auditor_note',
        'auditee_response',
        'assessed_by',
        'assessed_at',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'assessed_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    const STATUS_COMPLIANT = 'compliant';
    const STATUS_OBSERVATION = 'observation';
    const STATUS_MINOR_NC = 'minor_nc';
    const STATUS_MAJOR_NC = 'major_nc';

    /**
     * Audit
     */
    public function audit()
    {
        return $this->belongsTo(TrxAudit::class, 'audit_id');
    }

    /**
     * Metric/Indicator
     */
    public function metric()
    {
        return $this->belongsTo(MstMetric::class, 'metric_id');
    }

    /**
     * Evidences for this detail
     */
    public function evidences()
    {
        return $this->hasMany(TrxEvidence::class, 'audit_detail_id');
    }

    /**
     * Finding (if any)
     */
    public function finding()
    {
        return $this->hasOne(TrxFinding::class, 'audit_detail_id');
    }

    /**
     * Assessor (auditor)
     */
    public function assessedBy()
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }

    /**
     * Verifier
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Check if has finding
     */
    public function hasFinding()
    {
        return in_array($this->finding_status, [self::STATUS_OBSERVATION, self::STATUS_MINOR_NC, self::STATUS_MAJOR_NC]);
    }

    /**
     * Create finding from this detail
     */
    public function createFinding($type, $description, $dueDate)
    {
        return TrxFinding::create([
            'ptk_code' => TrxFinding::generateCode(),
            'audit_id' => $this->audit_id,
            'audit_detail_id' => $this->id,
            'type' => $type,
            'finding_description' => $description,
            'reference_requirement' => $this->metric->reference_document,
            'objective_evidence' => $this->auditor_note,
            'identified_by' => auth()->id(),
            'identified_at' => now(),
            'due_date' => $dueDate,
        ]);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_COMPLIANT => 'Sesuai',
            self::STATUS_OBSERVATION => 'Observasi (OB)',
            self::STATUS_MINOR_NC => 'KTS Minor',
            self::STATUS_MAJOR_NC => 'KTS Mayor',
        ];
        return $labels[$this->finding_status] ?? '-';
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            self::STATUS_COMPLIANT => 'green',
            self::STATUS_OBSERVATION => 'yellow',
            self::STATUS_MINOR_NC => 'orange',
            self::STATUS_MAJOR_NC => 'red',
        ];
        return $colors[$this->finding_status] ?? 'gray';
    }
}
