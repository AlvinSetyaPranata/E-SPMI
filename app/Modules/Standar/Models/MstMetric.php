<?php

namespace App\Modules\Standar\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MstMetric extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mst_metrics';

    protected $fillable = [
        'instrument_id',
        'code',
        'name',
        'description',
        'type',
        'parent_id',
        'order_no',
        'data_type',
        'data_options',
        'weight',
        'target_value',
        'target_per_level',
        'reference_document',
        'assessment_guide',
        'is_required',
        'is_active',
    ];

    protected $casts = [
        'data_options' => 'json',
        'target_per_level' => 'json',
        'weight' => 'decimal:2',
        'target_value' => 'decimal:4',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    const TYPE_STANDARD = 'standard';
    const TYPE_SUB_STANDARD = 'sub_standard';
    const TYPE_INDICATOR = 'indicator';
    const TYPE_SUB_INDICATOR = 'sub_indicator';

    const DATA_TYPE_NUMERIC = 'numeric';
    const DATA_TYPE_PERCENTAGE = 'percentage';
    const DATA_TYPE_BOOLEAN = 'boolean';
    const DATA_TYPE_TEXT = 'text';
    const DATA_TYPE_FILE = 'file';
    const DATA_TYPE_CHOICE = 'choice';

    /**
     * Instrument
     */
    public function instrument()
    {
        return $this->belongsTo(MstInstrument::class, 'instrument_id');
    }

    /**
     * Parent metric
     */
    public function parent()
    {
        return $this->belongsTo(MstMetric::class, 'parent_id');
    }

    /**
     * Children metrics
     */
    public function children()
    {
        return $this->hasMany(MstMetric::class, 'parent_id')->orderBy('order_no');
    }

    /**
     * All descendants
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Scope for leaf nodes (indicators that need input)
     */
    public function scopeLeafNodes($query)
    {
        return $query->whereIn('type', [self::TYPE_INDICATOR, self::TYPE_SUB_INDICATOR]);
    }

    /**
     * Scope by instrument
     */
    public function scopeOfInstrument($query, $instrumentId)
    {
        return $query->where('instrument_id', $instrumentId);
    }

    /**
     * Scope root level
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get target for specific level
     */
    public function getTargetForLevel($level)
    {
        $targets = $this->target_per_level ?? [];
        return $targets[$level] ?? $this->target_value;
    }

    /**
     * Check if this is a leaf node (terminal indicator)
     */
    public function isLeafNode()
    {
        return in_array($this->type, [self::TYPE_INDICATOR, self::TYPE_SUB_INDICATOR]);
    }

    /**
     * Get full path name
     */
    public function getFullPathAttribute()
    {
        $path = [$this->name];
        $parent = $this->parent;
        
        while ($parent) {
            $path[] = $parent->name;
            $parent = $parent->parent;
        }
        
        return implode(' > ', array_reverse($path));
    }
}
