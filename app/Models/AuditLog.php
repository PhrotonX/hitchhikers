<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    /** @use HasFactory<\Database\Factories\AuditLogFactory> */
    use HasFactory;

    protected $table = "audit_logs";

    protected $fillable = [
        "user_id",
        "event",
        'table',
        'data_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that performed the action
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include logs for a specific user
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include logs for a specific table
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $table
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForTable($query, string $table)
    {
        return $query->where('table', $table);
    }

    /**
     * Scope a query to only include logs for a specific event
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $event
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForEvent($query, string $event)
    {
        return $query->where('event', $event);
    }

    /**
     * Scope a query to only include logs for a specific record
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $table
     * @param int $dataId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForRecord($query, string $table, int $dataId)
    {
        return $query->where('table', $table)->where('data_id', $dataId);
    }

    /**
     * Scope a query to only include logs from a specific date range
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $from
     * @param string $to
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateRange($query, string $from, string $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * Get a human-readable description of the audit log
     *
     * @return string
     */
    public function getDescriptionAttribute(): string
    {
        $userName = $this->user ? $this->user->getFullName() : 'System';
        $action = $this->event;
        $table = str_replace('_', ' ', $this->table);

        return "{$userName} {$action} {$table} (ID: {$this->data_id})";
    }

    /**
     * Get the changes made in this audit log
     *
     * @return array
     */
    public function getChanges(): array
    {
        if ($this->event === 'updated' && $this->old_values && $this->new_values) {
            $changes = [];
            foreach ($this->new_values as $key => $newValue) {
                $oldValue = $this->old_values[$key] ?? null;
                if ($oldValue !== $newValue) {
                    $changes[$key] = [
                        'old' => $oldValue,
                        'new' => $newValue,
                    ];
                }
            }
            return $changes;
        }

        return [];
    }

    /**
     * Check if this log is for a specific model
     *
     * @param Model $model
     * @return bool
     */
    public function isFor(Model $model): bool
    {
        return $this->table === $model->getTable() && $this->data_id == $model->getKey();
    }
}
