<?php

namespace App\Modules\Standar\Models;

use App\Modules\Audit\Models\TrxAudit;
use App\Modules\Core\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MstPeriod extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mst_periods';

    protected $fillable = [
        'name',
        'academic_year',
        'semester',
        'start_date',
        'end_date',
        'planning_start',
        'planning_end',
        'implementation_start',
        'implementation_end',
        'evaluation_start',
        'evaluation_end',
        'control_start',
        'control_end',
        'improvement_start',
        'improvement_end',
        'status',
        'is_locked',
        'instrument_id',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'planning_start' => 'datetime',
        'planning_end' => 'datetime',
        'implementation_start' => 'datetime',
        'implementation_end' => 'datetime',
        'evaluation_start' => 'datetime',
        'evaluation_end' => 'datetime',
        'control_start' => 'datetime',
        'control_end' => 'datetime',
        'improvement_start' => 'datetime',
        'improvement_end' => 'datetime',
        'is_locked' => 'boolean',
    ];

    const SEMESTER_ODD = 'odd';
    const SEMESTER_EVEN = 'even';
    const SEMESTER_ANNUAL = 'annual';

    const STATUS_DRAFT = 'draft';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_ARCHIVED = 'archived';

    /**
     * Instrument used in this period
     */
    public function instrument()
    {
        return $this->belongsTo(MstInstrument::class, 'instrument_id');
    }

    /**
     * Audits in this period
     */
    public function audits()
    {
        return $this->hasMany(TrxAudit::class, 'period_id');
    }

    /**
     * Creator
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get current phase based on date
     */
    public function getCurrentPhaseAttribute()
    {
        $now = now();
        
        if ($this->control_start && $now >= $this->control_start) {
            return 'control';
        }
        if ($this->evaluation_start && $now >= $this->evaluation_start) {
            return 'evaluation';
        }
        if ($this->implementation_start && $now >= $this->implementation_start) {
            return 'implementation';
        }
        if ($this->planning_start && $now >= $this->planning_start) {
            return 'planning';
        }
        
        return null;
    }

    /**
     * Check if specific phase is active
     */
    public function isPhaseActive($phase)
    {
        $now = now();
        
        switch ($phase) {
            case 'planning':
                return $this->planning_start && $this->planning_end 
                    && $now->between($this->planning_start, $this->planning_end);
            case 'implementation':
                return $this->implementation_start && $this->implementation_end 
                    && $now->between($this->implementation_start, $this->implementation_end);
            case 'evaluation':
                return $this->evaluation_start && $this->evaluation_end 
                    && $now->between($this->evaluation_start, $this->evaluation_end);
            case 'control':
                return $this->control_start && $this->control_end 
                    && $now->between($this->control_start, $this->control_end);
            case 'improvement':
                return $this->improvement_start && $this->improvement_end 
                    && $now->between($this->improvement_start, $this->improvement_end);
        }
        
        return false;
    }

    /**
     * Scope active periods
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Get formatted semester name
     */
    public function getSemesterNameAttribute()
    {
        $names = [
            self::SEMESTER_ODD => 'Ganjil',
            self::SEMESTER_EVEN => 'Genap',
            self::SEMESTER_ANNUAL => 'Tahunan',
        ];
        return $names[$this->semester] ?? $this->semester;
    }
}
