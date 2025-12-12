<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    /**
     * Boot the auditable trait for a model.
     */
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $model->auditCreated();
        });

        static::updated(function ($model) {
            $model->auditUpdated();
        });

        static::deleted(function ($model) {
            $model->auditDeleted();
        });
    }

    /**
     * Log creation event
     */
    protected function auditCreated()
    {
        $this->createAuditLog('created', null, $this->getAuditableAttributes());
    }

    /**
     * Log update event
     */
    protected function auditUpdated()
    {
        $changes = $this->getChanges();
        $original = $this->getOriginal();

        // Remove timestamps from audit if not explicitly tracking them
        if (!$this->shouldAuditTimestamps()) {
            unset($changes['created_at'], $changes['updated_at']);
            unset($original['created_at'], $original['updated_at']);
        }

        // Only log if there are actual changes
        if (!empty($changes)) {
            $oldValues = [];
            foreach (array_keys($changes) as $key) {
                $oldValues[$key] = $original[$key] ?? null;
            }

            $this->createAuditLog('updated', $oldValues, $changes);
        }
    }

    /**
     * Log deletion event
     */
    protected function auditDeleted()
    {
        $this->createAuditLog('deleted', $this->getAuditableAttributes(), null);
    }

    /**
     * Create an audit log entry
     *
     * @param string $event The event type (created, updated, deleted)
     * @param array|null $oldValues The old values
     * @param array|null $newValues The new values
     */
    protected function createAuditLog(string $event, ?array $oldValues, ?array $newValues)
    {
        // Skip if auditing is disabled
        if (!$this->isAuditingEnabled()) {
            return;
        }

        // Get excluded attributes
        $excludedAttributes = $this->getAuditExclude();

        // Filter out excluded attributes
        if (!empty($excludedAttributes)) {
            if ($oldValues) {
                $oldValues = array_diff_key($oldValues, array_flip($excludedAttributes));
            }
            if ($newValues) {
                $newValues = array_diff_key($newValues, array_flip($excludedAttributes));
            }
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'event' => $event,
            'table' => $this->getTable(),
            'data_id' => $this->getKey(),
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
        ]);
    }

    /**
     * Get auditable attributes
     *
     * @return array
     */
    protected function getAuditableAttributes(): array
    {
        $attributes = $this->getAttributes();

        // Remove excluded attributes
        $excludedAttributes = $this->getAuditExclude();
        if (!empty($excludedAttributes)) {
            $attributes = array_diff_key($attributes, array_flip($excludedAttributes));
        }

        // Remove timestamps if not tracking them
        if (!$this->shouldAuditTimestamps()) {
            unset($attributes['created_at'], $attributes['updated_at']);
        }

        return $attributes;
    }

    /**
     * Get attributes to exclude from auditing
     *
     * @return array
     */
    protected function getAuditExclude(): array
    {
        return property_exists($this, 'auditExclude') ? $this->auditExclude : [];
    }

    /**
     * Check if auditing is enabled for this model
     *
     * @return bool
     */
    protected function isAuditingEnabled(): bool
    {
        return property_exists($this, 'auditingEnabled') ? $this->auditingEnabled : true;
    }

    /**
     * Check if timestamps should be audited
     *
     * @return bool
     */
    protected function shouldAuditTimestamps(): bool
    {
        return property_exists($this, 'auditTimestamps') ? $this->auditTimestamps : false;
    }

    /**
     * Get audit logs for this model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function auditLogs()
    {
        return AuditLog::where('table', $this->getTable())
            ->where('data_id', $this->getKey())
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
