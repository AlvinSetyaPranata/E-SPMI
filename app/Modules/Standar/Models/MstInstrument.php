<?php

namespace App\Modules\Standar\Models;

use App\Modules\Core\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MstInstrument extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mst_instruments';

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'reference_regulation',
        'status',
        'effective_date',
        'expired_date',
        'created_by',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'expired_date' => 'date',
    ];

    const TYPE_INSTITUTIONAL = 'institutional';
    const TYPE_STUDY_PROGRAM = 'study_program';
    const TYPE_INTERNAL = 'internal';

    const STATUS_DRAFT = 'draft';
    const STATUS_ACTIVE = 'active';
    const STATUS_ARCHIVED = 'archived';

    /**
     * Metrics/Standards in this instrument
     */
    public function metrics()
    {
        return $this->hasMany(MstMetric::class, 'instrument_id');
    }

    /**
     * Root metrics (top-level standards)
     */
    public function rootMetrics()
    {
        return $this->hasMany(MstMetric::class, 'instrument_id')->whereNull('parent_id');
    }

    /**
     * Periods using this instrument
     */
    public function periods()
    {
        return $this->hasMany(MstPeriod::class, 'instrument_id');
    }

    /**
     * Creator
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope active instruments
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
            ->where(function ($q) {
                $q->whereNull('expired_date')
                  ->orWhere('expired_date', '>', now());
            });
    }

    /**
     * Scope by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Clone instrument with all metrics
     */
    public function cloneWithMetrics($newCode, $newName, $userId)
    {
        $newInstrument = $this->replicate();
        $newInstrument->code = $newCode;
        $newInstrument->name = $newName;
        $newInstrument->status = self::STATUS_DRAFT;
        $newInstrument->created_by = $userId;
        $newInstrument->save();

        // Clone metrics recursively
        $this->cloneMetricsRecursive($this->rootMetrics, $newInstrument->id, null);

        return $newInstrument;
    }

    private function cloneMetricsRecursive($metrics, $instrumentId, $parentId)
    {
        foreach ($metrics as $metric) {
            $newMetric = $metric->replicate();
            $newMetric->instrument_id = $instrumentId;
            $newMetric->parent_id = $parentId;
            $newMetric->save();

            if ($metric->children->isNotEmpty()) {
                $this->cloneMetricsRecursive($metric->children, $instrumentId, $newMetric->id);
            }
        }
    }
}
