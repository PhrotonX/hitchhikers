<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    /**
     * Log a custom event
     *
     * @param string $event The event name
     * @param string $table The table name
     * @param int|string $dataId The record ID
     * @param array|null $oldValues Old values
     * @param array|null $newValues New values
     * @param int|null $userId User ID (defaults to authenticated user)
     * @return AuditLog
     */
    public function log(
        string $event,
        string $table,
        $dataId,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $userId = null
    ): AuditLog {
        return AuditLog::create([
            'user_id' => $userId ?? Auth::id(),
            'event' => $event,
            'table' => $table,
            'data_id' => $dataId,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
        ]);
    }

    /**
     * Log user login
     *
     * @param int $userId
     * @return AuditLog
     */
    public function logLogin(int $userId): AuditLog
    {
        return $this->log(
            'login',
            'users',
            $userId,
            null,
            ['status' => 'logged_in'],
            $userId
        );
    }

    /**
     * Log user logout
     *
     * @param int $userId
     * @return AuditLog
     */
    public function logLogout(int $userId): AuditLog
    {
        return $this->log(
            'logout',
            'users',
            $userId,
            null,
            ['status' => 'logged_out'],
            $userId
        );
    }

    /**
     * Log failed login attempt
     *
     * @param string $email
     * @return AuditLog
     */
    public function logFailedLogin(string $email): AuditLog
    {
        return AuditLog::create([
            'user_id' => null,
            'event' => 'failed_login',
            'table' => 'users',
            'data_id' => 0,
            'old_values' => null,
            'new_values' => json_encode(['email' => $email]),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
        ]);
    }

    /**
     * Log password change
     *
     * @param int $userId
     * @return AuditLog
     */
    public function logPasswordChange(int $userId): AuditLog
    {
        return $this->log(
            'password_changed',
            'users',
            $userId,
            null,
            ['status' => 'password_changed'],
            $userId
        );
    }

    /**
     * Log email verification
     *
     * @param int $userId
     * @return AuditLog
     */
    public function logEmailVerified(int $userId): AuditLog
    {
        return $this->log(
            'email_verified',
            'users',
            $userId,
            null,
            ['status' => 'email_verified'],
            $userId
        );
    }

    /**
     * Get audit logs for a specific user
     *
     * @param int $userId
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserLogs(int $userId, int $limit = 50)
    {
        return AuditLog::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get audit logs for a specific table and record
     *
     * @param string $table
     * @param int|string $dataId
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecordLogs(string $table, $dataId, int $limit = 50)
    {
        return AuditLog::where('table', $table)
            ->where('data_id', $dataId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get all audit logs with pagination
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllLogs(int $perPage = 50)
    {
        return AuditLog::orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get audit logs by event type
     *
     * @param string $event
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLogsByEvent(string $event, int $limit = 50)
    {
        return AuditLog::where('event', $event)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Clean old audit logs
     *
     * @param int $daysToKeep Number of days to keep logs
     * @return int Number of deleted records
     */
    public function cleanOldLogs(int $daysToKeep = 90): int
    {
        return AuditLog::where('created_at', '<', now()->subDays($daysToKeep))
            ->delete();
    }

    /**
     * Get recent activity
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentActivity(int $limit = 20)
    {
        return AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
