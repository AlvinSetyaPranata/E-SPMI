<?php

namespace App\Modules\Pelaksanaan\Models;

use App\Modules\Audit\Models\TrxAudit;
use App\Modules\Audit\Models\TrxAuditDetail;
use App\Modules\Core\Models\User;
use App\Modules\Standar\Models\MstMetric;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrxEvidence extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trx_evidences';

    protected $fillable = [
        'audit_id',
        'audit_detail_id',
        'title',
        'description',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'file_extension',
        'metric_id',
        'related_metrics',
        'category',
        'version',
        'previous_version_id',
        'status',
        'source',
        'source_metadata',
        'is_disputed',
        'dispute_reason',
        'dispute_verified_by',
        'uploaded_by',
        'uploaded_at',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'related_metrics' => 'json',
        'source_metadata' => 'json',
        'file_size' => 'integer',
        'version' => 'integer',
        'is_disputed' => 'boolean',
        'uploaded_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    const CATEGORY_CURRICULUM = 'curriculum';
    const CATEGORY_RESEARCH = 'research';
    const CATEGORY_COMMUNITY_SERVICE = 'community_service';
    const CATEGORY_STUDENT = 'student';
    const CATEGORY_FACILITY = 'facility';
    const CATEGORY_MANAGEMENT = 'management';
    const CATEGORY_OTHER = 'other';

    const STATUS_DRAFT = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_VERIFIED = 'verified';
    const STATUS_REJECTED = 'rejected';
    const STATUS_REVISED = 'revised';

    const SOURCE_MANUAL = 'manual_upload';
    const SOURCE_SIAKAD = 'siakad';
    const SOURCE_HRIS = 'hris';
    const SOURCE_FINANCE = 'finance';
    const SOURCE_OTHER = 'other';

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
     * Primary metric
     */
    public function metric()
    {
        return $this->belongsTo(MstMetric::class, 'metric_id');
    }

    /**
     * Uploader
     */
    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Verifier
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Previous version
     */
    public function previousVersion()
    {
        return $this->belongsTo(TrxEvidence::class, 'previous_version_id');
    }

    /**
     * Next version
     */
    public function nextVersion()
    {
        return $this->hasOne(TrxEvidence::class, 'previous_version_id');
    }

    /**
     * All versions of this document
     */
    public function allVersions()
    {
        $versions = collect();
        $current = $this;
        
        // Get previous versions
        while ($current->previousVersion) {
            $versions->prepend($current->previousVersion);
            $current = $current->previousVersion;
        }
        
        $versions->push($this);
        
        // Get next versions
        $current = $this;
        while ($current->nextVersion) {
            $versions->push($current->nextVersion);
            $current = $current->nextVersion;
        }
        
        return $versions;
    }

    /**
     * Create new version
     */
    public function createNewVersion(array $data)
    {
        $newVersion = $this->replicate();
        $newVersion->fill($data);
        $newVersion->version = $this->version + 1;
        $newVersion->previous_version_id = $this->id;
        $newVersion->status = self::STATUS_DRAFT;
        $newVersion->save();
        
        return $newVersion;
    }

    /**
     * Get file size in human readable format
     */
    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get file icon based on extension
     */
    public function getFileIconAttribute()
    {
        $icons = [
            'pdf' => 'document-text',
            'doc' => 'document-word',
            'docx' => 'document-word',
            'xls' => 'document-excel',
            'xlsx' => 'document-excel',
            'ppt' => 'document-presentation',
            'pptx' => 'document-presentation',
            'jpg' => 'image',
            'jpeg' => 'image',
            'png' => 'image',
            'zip' => 'archive',
            'rar' => 'archive',
        ];
        
        return $icons[strtolower($this->file_extension)] ?? 'document';
    }

    /**
     * Scope by category
     */
    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope by audit
     */
    public function scopeOfAudit($query, $auditId)
    {
        return $query->where('audit_id', $auditId);
    }

    /**
     * Submit evidence
     */
    public function submit()
    {
        $this->status = self::STATUS_SUBMITTED;
        $this->uploaded_at = now();
        $this->save();
    }

    /**
     * Verify evidence
     */
    public function verify($userId, $approve = true)
    {
        if ($approve) {
            $this->status = self::STATUS_VERIFIED;
        } else {
            $this->status = self::STATUS_REJECTED;
        }
        $this->verified_by = $userId;
        $this->verified_at = now();
        $this->save();
    }

    /**
     * Dispute evidence (for integrated data)
     */
    public function dispute($reason)
    {
        $this->is_disputed = true;
        $this->dispute_reason = $reason;
        $this->save();
    }
}
