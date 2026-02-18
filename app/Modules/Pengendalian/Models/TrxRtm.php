<?php

namespace App\Modules\Pengendalian\Models;

use App\Modules\Core\Models\RefUnit;
use App\Modules\Core\Models\User;
use App\Modules\Standar\Models\MstPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrxRtm extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trx_rtm';

    protected $fillable = [
        'period_id',
        'rtm_code',
        'title',
        'description',
        'level',
        'unit_id',
        'meeting_date',
        'location',
        'meeting_link',
        'agenda',
        'meeting_notes',
        'status',
        'created_by',
    ];

    protected $casts = [
        'meeting_date' => 'datetime',
        'agenda' => 'json',
    ];

    const LEVEL_UNIVERSITY = 'university';
    const LEVEL_FACULTY = 'faculty';
    const LEVEL_DEPARTMENT = 'department';

    const STATUS_DRAFT = 'draft';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_ONGOING = 'ongoing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

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
     * Creator
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Attendances
     */
    public function attendances()
    {
        return $this->hasMany(TrxRtmAttendance::class, 'rtm_id');
    }

    /**
     * Action plans
     */
    public function actionPlans()
    {
        return $this->hasMany(TrxRtmActionPlan::class, 'rtm_id');
    }

    /**
     * Get attendees
     */
    public function attendees()
    {
        return $this->belongsToMany(User::class, 'trx_rtm_attendances', 'rtm_id', 'user_id')
            ->withPivot('role', 'attendance_status', 'check_in_at', 'signature_file')
            ->withTimestamps();
    }

    /**
     * Get present attendees
     */
    public function presentAttendees()
    {
        return $this->attendees()->wherePivot('attendance_status', TrxRtmAttendance::STATUS_PRESENT);
    }

    /**
     * Generate RTM code
     */
    public static function generateCode()
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'RTM-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Check if meeting is upcoming
     */
    public function isUpcoming()
    {
        return $this->meeting_date > now();
    }

    /**
     * Check if meeting is ongoing
     */
    public function isOngoing()
    {
        return $this->status === self::STATUS_ONGOING;
    }

    /**
     * Start meeting
     */
    public function start()
    {
        $this->status = self::STATUS_ONGOING;
        $this->save();
    }

    /**
     * Complete meeting
     */
    public function complete($notes = null)
    {
        if ($notes) {
            $this->meeting_notes = $notes;
        }
        $this->status = self::STATUS_COMPLETED;
        $this->save();
    }

    /**
     * Scope by level
     */
    public function scopeOfLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope upcoming meetings
     */
    public function scopeUpcoming($query)
    {
        return $query->where('meeting_date', '>', now())
            ->where('status', '!=', self::STATUS_CANCELLED);
    }

    /**
     * Get level label
     */
    public function getLevelLabelAttribute()
    {
        $labels = [
            self::LEVEL_UNIVERSITY => 'Universitas',
            self::LEVEL_FACULTY => 'Fakultas',
            self::LEVEL_DEPARTMENT => 'Jurusan',
        ];
        return $labels[$this->level] ?? $this->level;
    }
}

/**
 * RTM Attendance Model
 */
class TrxRtmAttendance extends Model
{
    use HasFactory;

    protected $table = 'trx_rtm_attendances';

    protected $fillable = [
        'rtm_id',
        'user_id',
        'role',
        'attendance_status',
        'notes',
        'check_in_at',
        'signature_file',
    ];

    protected $casts = [
        'check_in_at' => 'datetime',
    ];

    const ROLE_CHAIRMAN = 'chairman';
    const ROLE_SECRETARY = 'secretary';
    const ROLE_MEMBER = 'member';
    const ROLE_GUEST = 'guest';

    const STATUS_PRESENT = 'present';
    const STATUS_ABSENT = 'absent';
    const STATUS_EXCUSED = 'excused';
    const STATUS_LATE = 'late';

    public function rtm()
    {
        return $this->belongsTo(TrxRtm::class, 'rtm_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function checkIn()
    {
        $this->attendance_status = self::STATUS_PRESENT;
        $this->check_in_at = now();
        $this->save();
    }
}

/**
 * RTM Action Plan Model
 */
class TrxRtmActionPlan extends Model
{
    use HasFactory;

    protected $table = 'trx_rtm_action_plans';

    protected $fillable = [
        'rtm_id',
        'action_description',
        'responsible_id',
        'unit_id',
        'due_date',
        'priority',
        'status',
        'result',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'verified_at' => 'datetime',
    ];

    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_CRITICAL = 'critical';

    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public function rtm()
    {
        return $this->belongsTo(TrxRtm::class, 'rtm_id');
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function unit()
    {
        return $this->belongsTo(RefUnit::class, 'unit_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function markAsInProgress()
    {
        $this->status = self::STATUS_IN_PROGRESS;
        $this->save();
    }

    public function complete($result, $verifiedBy)
    {
        $this->result = $result;
        $this->status = self::STATUS_COMPLETED;
        $this->verified_by = $verifiedBy;
        $this->verified_at = now();
        $this->save();
    }

    public function getPriorityLabelAttribute()
    {
        $labels = [
            self::PRIORITY_LOW => 'Rendah',
            self::PRIORITY_MEDIUM => 'Sedang',
            self::PRIORITY_HIGH => 'Tinggi',
            self::PRIORITY_CRITICAL => 'Kritis',
        ];
        return $labels[$this->priority] ?? $this->priority;
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_IN_PROGRESS => 'Dalam Proses',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];
        return $labels[$this->status] ?? $this->status;
    }
}
