<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'description',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
        'created_at' => 'datetime',
    ];

    /**
     * User who performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the entity model (polymorphic-like)
     */
    public function entity()
    {
        return $this->entity_type ? app($this->entity_type)::find($this->entity_id) : null;
    }

    /**
     * Scope by action
     */
    public function scopeOfAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope by entity
     */
    public function scopeForEntity($query, $entityType, $entityId = null)
    {
        $query = $query->where('entity_type', $entityType);
        if ($entityId) {
            $query->where('entity_id', $entityId);
        }
        return $query;
    }

    /**
     * Scope for recent logs
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
