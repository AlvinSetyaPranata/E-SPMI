<?php

namespace App\Modules\Audit\Models;

use App\Modules\Core\Models\RefUnit;
use App\Modules\Core\Models\User;
use App\Modules\Pelaksanaan\Models\TrxEvidence;
use App\Modules\Pengendalian\Models\TrxFinding;
use App\Modules\Standar\Models\MstInstrument;
use App\Modules\Standar\Models\MstPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrxAudit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trx_audits';

    protected $fillable = [
        'audit_code',
        'period_id',
        'instrument_id',
        'unit_id',
        'phase',
        'status',
        'self_assessment_score',
        'audit_score',
        'final_score',
        'predicate',
        'planning_locked_at',
        'planning_locked_by',
        'implementation_locked_at',
        'implementation_locked_by',
        'evaluation_locked_at',
        'evaluation_locked_by',
        'created_by',
    ];

    protected $casts = [
        'self_assessment_score' => 'decimal:2',
        'audit_score' => 'decimal:2',
        'final_score' => 'decimal:2',
        'planning_locked_at' => 'datetime',
        'implementation_locked_at' => 'datetime',
        'evaluation_locked_at' => 'datetime',
    ];

    const PHASE_PLANNING = 'planning';
    const PHASE_IMPLEMENTATION = 'implementation';
    const PHASE_EVALUATION = 'evaluation';
    const PHASE_CONTROL = 'control';
    const PHASE_IMPROVEMENT = 'improvement';
    const PHASE_COMPLETED = 'completed';

    const STATUS_DRAFT = 'draft';
    const STATUS_ONGOING = 'ongoing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_VERIFIED = 'verified';
    const STATUS_APPROVED = 'approved';

    const PREDICATE_NOT_ACCREDITED = 'not_accredited';
    const PREDICATE_ACCREDITED = 'accredited';
    const PREDICATE_GOOD = 'good';
    const PREDICATE_VERY_GOOD = 'very_good';
    const PREDICATE_EXCELLENT = 'excellent';

    /**
     * Period
     */
    public function period()
    {
        return $this->belongsTo(MstPeriod::class, 'period_id');
    }

    /**
     * Instrument
     */
    public function instrument()
    {
        return $this->belongsTo(MstInstrument::class, 'instrument_id');
    }

    /**
     * Unit being audited
     */
    public function unit()
    {
        return $this->belongsTo(RefUnit::class, 'unit_id');
    }

    /**
     * Audit details (scores per metric)
     */
    public function details()
    {
        return $this->hasMany(TrxAuditDetail::class, 'audit_id');
    }

    /**
     * Assigned auditors
     */
    public function auditors()
    {
        return $this->hasMany(TrxAuditor::class, 'audit_id');
    }

    /**
     * Lead auditor
     */
    public function leadAuditor()
    {
        return $this->hasOne(TrxAuditor::class, 'audit_id')->where('role', TrxAuditor::ROLE_LEAD);
    }

    /**
     * Evidences submitted
     */
    public function evidences()
    {
        return $this->hasMany(TrxEvidence::class, 'audit_id');
    }

    /**
     * Findings (KTS/OB)
     */
    public function findings()
    {
        return $this->hasMany(TrxFinding::class, 'audit_id');
    }

    /**
     * Creator
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if can transition to next phase
     */
    public function canTransitionTo($phase)
    {
        // Strict workflow enforcement - must complete current phase
        $transitions = [
            self::PHASE_PLANNING => [self::PHASE_IMPLEMENTATION],
            self::PHASE_IMPLEMENTATION => [self::PHASE_EVALUATION],
            self::PHASE_EVALUATION => [self::PHASE_CONTROL],
            self::PHASE_CONTROL => [self::PHASE_IMPROVEMENT],
            self::PHASE_IMPROVEMENT => [self::PHASE_COMPLETED],
        ];

        return in_array($phase, $transitions[$this->phase] ?? []);
    }

    /**
     * Transition to next phase
     */
    public function transitionTo($phase)
    {
        if (!$this->canTransitionTo($phase)) {
            throw new \Exception("Cannot transition from {$this->phase} to {$phase}");
        }

        $this->phase = $phase;
        
        // Update locked timestamps
        $now = now();
        switch ($phase) {
            case self::PHASE_IMPLEMENTATION:
                $this->planning_locked_at = $now;
                break;
            case self::PHASE_EVALUATION:
                $this->implementation_locked_at = $now;
                break;
            case self::PHASE_CONTROL:
                $this->evaluation_locked_at = $now;
                break;
        }
        
        $this->save();
    }

    /**
     * Calculate final score
     */
    public function calculateScore()
    {
        $details = $this->details()->whereNotNull('score');
        
        if ($details->count() === 0) {
            return null;
        }

        $totalWeight = 0;
        $weightedScore = 0;

        foreach ($details->get() as $detail) {
            $weight = $detail->metric->weight ?? 1;
            $totalWeight += $weight;
            $weightedScore += ($detail->score * $weight);
        }

        $this->final_score = $totalWeight > 0 ? ($weightedScore / $totalWeight) : 0;
        $this->save();

        return $this->final_score;
    }

    /**
     * Determine predicate based on score
     */
    public function determinePredicate()
    {
        $score = $this->final_score;
        
        // Example scoring rubric (customizable)
        if ($score >= 85) {
            $this->predicate = self::PREDICATE_EXCELLENT;
        } elseif ($score >= 75) {
            $this->predicate = self::PREDICATE_VERY_GOOD;
        } elseif ($score >= 65) {
            $this->predicate = self::PREDICATE_GOOD;
        } elseif ($score >= 55) {
            $this->predicate = self::PREDICATE_ACCREDITED;
        } else {
            $this->predicate = self::PREDICATE_NOT_ACCREDITED;
        }
        
        $this->save();
        return $this->predicate;
    }

    /**
     * Get findings count by type
     */
    public function getFindingsCountByType($type)
    {
        return $this->findings()->where('type', $type)->count();
    }

    /**
     * Scope by period
     */
    public function scopeOfPeriod($query, $periodId)
    {
        return $query->where('period_id', $periodId);
    }

    /**
     * Scope by unit
     */
    public function scopeOfUnit($query, $unitId)
    {
        return $query->where('unit_id', $unitId);
    }

    /**
     * Scope by phase
     */
    public function scopeInPhase($query, $phase)
    {
        return $query->where('phase', $phase);
    }
}
