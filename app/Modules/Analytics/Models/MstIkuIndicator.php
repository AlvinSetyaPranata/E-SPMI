<?php

namespace App\Modules\Analytics\Models;

use App\Modules\Core\Models\RefUnit;
use App\Modules\Core\Models\User;
use App\Modules\Standar\Models\MstPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstIkuIndicator extends Model
{
    use HasFactory;

    protected $table = 'mst_iku_indicators';

    protected $fillable = [
        'code',
        'name',
        'description',
        'formula',
        'measurement_unit',
        'target_national',
        'target_per_year',
        'data_source',
        'is_active',
        'order_no',
    ];

    protected $casts = [
        'target_national' => 'decimal:2',
        'target_per_year' => 'json',
        'is_active' => 'boolean',
        'order_no' => 'integer',
    ];

    // 8 IKU Codes
    const IKU_1 = 1;  // Daya Saing Lulusan
    const IKU_2 = 2;  // Penjelajahan Data
    const IKU_3 = 3;  // Karya Inovatif
    const IKU_4 = 4;  // Kolaborasi Luar Negeri
    const IKU_5 = 5;  // Publikasi Bereputasi
    const IKU_6 = 6;  // Luaran Penelitian
    const IKU_7 = 7;  // Kepuasan Pengguna
    const IKU_8 = 8;  // Kemampuan Bahasa Inggris

    const SOURCE_MANUAL = 'manual';
    const SOURCE_SIAKAD = 'siakad';
    const SOURCE_HRIS = 'hris';
    const SOURCE_FINANCE = 'finance';
    const SOURCE_SISTER = 'sister';

    /**
     * IKU data entries
     */
    public function data()
    {
        return $this->hasMany(TrxIkuData::class, 'iku_indicator_id');
    }

    /**
     * Get data for specific period
     */
    public function dataForPeriod($periodId, $unitId = null)
    {
        return $this->data()
            ->where('period_id', $periodId)
            ->where('unit_id', $unitId)
            ->first();
    }

    /**
     * Get target for specific year
     */
    public function getTargetForYear($year)
    {
        $targets = $this->target_per_year ?? [];
        return $targets[$year] ?? $this->target_national;
    }

    /**
     * Get trend data for chart
     */
    public function getTrendData($years = 5, $unitId = null)
    {
        $currentYear = date('Y');
        $data = [];

        for ($i = $years - 1; $i >= 0; $i--) {
            $year = $currentYear - $i;
            $actual = $this->data()
                ->whereHas('period', function ($q) use ($year) {
                    $q->where('academic_year', 'like', $year . '%');
                })
                ->when($unitId, function ($q) use ($unitId) {
                    $q->where('unit_id', $unitId);
                })
                ->avg('actual');

            $data[] = [
                'year' => $year,
                'target' => $this->getTargetForYear($year),
                'actual' => round($actual, 2),
            ];
        }

        return $data;
    }

    /**
     * Scope active indicators
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope ordered
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_no');
    }

    /**
     * Get all 8 IKU
     */
    public static function getAllIku()
    {
        return self::ordered()->get();
    }

    /**
     * Get IKU by code
     */
    public static function getByCode($code)
    {
        return self::where('code', $code)->first();
    }

    /**
     * Get IKU names
     */
    public static function getIkuNames()
    {
        return [
            self::IKU_1 => 'Daya Saing Lulusan',
            self::IKU_2 => 'Penjelajahan Data',
            self::IKU_3 => 'Karya Inovatif',
            self::IKU_4 => 'Kolaborasi Luar Negeri',
            self::IKU_5 => 'Publikasi Bereputasi',
            self::IKU_6 => 'Luaran Penelitian',
            self::IKU_7 => 'Kepuasan Pengguna',
            self::IKU_8 => 'Kemampuan Bahasa Inggris',
        ];
    }

    public function getIkuNameAttribute()
    {
        $names = self::getIkuNames();
        return $names[$this->code] ?? $this->name;
    }
}

/**
 * IKU Data Transaction Model
 */
class TrxIkuData extends Model
{
    use HasFactory;

    protected $table = 'trx_iku_data';

    protected $fillable = [
        'iku_indicator_id',
        'period_id',
        'unit_id',
        'target',
        'actual',
        'achievement',
        'analysis',
        'status',
        'input_by',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'target' => 'decimal:4',
        'actual' => 'decimal:4',
        'achievement' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_VERIFIED = 'verified';
    const STATUS_APPROVED = 'approved';

    /**
     * IKU Indicator
     */
    public function indicator()
    {
        return $this->belongsTo(MstIkuIndicator::class, 'iku_indicator_id');
    }

    /**
     * Period
     */
    public function period()
    {
        return $this->belongsTo(MstPeriod::class, 'period_id');
    }

    /**
     * Unit
     */
    public function unit()
    {
        return $this->belongsTo(RefUnit::class, 'unit_id');
    }

    /**
     * Input user
     */
    public function inputBy()
    {
        return $this->belongsTo(User::class, 'input_by');
    }

    /**
     * Verifier
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Calculate achievement percentage
     */
    public function calculateAchievement()
    {
        if ($this->target > 0) {
            $this->achievement = ($this->actual / $this->target) * 100;
        } else {
            $this->achievement = $this->actual > 0 ? 100 : 0;
        }
        $this->save();
        return $this->achievement;
    }

    /**
     * Get achievement status
     */
    public function getAchievementStatusAttribute()
    {
        if ($this->achievement >= 100) {
            return 'achieved';
        } elseif ($this->achievement >= 80) {
            return 'nearly_achieved';
        } else {
            return 'not_achieved';
        }
    }

    /**
     * Verify data
     */
    public function verify($userId)
    {
        $this->status = self::STATUS_VERIFIED;
        $this->verified_by = $userId;
        $this->verified_at = now();
        $this->save();
    }

    /**
     * Approve data
     */
    public function approve()
    {
        $this->status = self::STATUS_APPROVED;
        $this->save();
    }
}
