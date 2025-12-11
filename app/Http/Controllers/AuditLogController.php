<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuditLogRequest;
use App\Http\Requests\UpdateAuditLogRequest;
use App\Models\AuditLog;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    protected $auditService;

    public function __construct(AuditLogService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Display a listing of audit logs with optional filters
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 50);
        
        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        // Apply filters if provided
        if ($request->has('user_id')) {
            $query->forUser($request->user_id);
        }

        if ($request->has('table')) {
            $query->forTable($request->table);
        }

        if ($request->has('event')) {
            $query->forEvent($request->event);
        }

        if ($request->has('from') && $request->has('to')) {
            $query->dateRange($request->from, $request->to);
        }

        $logs = $query->paginate($perPage);

        return response()->json($logs);
    }

    /**
     * Get audit logs for a specific record
     */
    public function showRecordLogs(string $table, int $id)
    {
        $logs = AuditLog::forRecord($table, $id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'table' => $table,
            'record_id' => $id,
            'logs' => $logs,
        ]);
    }

    /**
     * Get audit logs for the authenticated user
     */
    public function myLogs(Request $request)
    {
        $limit = $request->get('limit', 50);
        $logs = $this->auditService->getUserLogs(Auth::id(), $limit);

        return response()->json($logs);
    }

    /**
     * Get recent activity across the system
     */
    public function recentActivity(Request $request)
    {
        $limit = $request->get('limit', 20);
        $activity = $this->auditService->getRecentActivity($limit);

        return response()->json($activity);
    }

    /**
     * Get audit statistics
     */
    public function statistics()
    {
        $stats = [
            'total_logs' => AuditLog::count(),
            'logs_today' => AuditLog::whereDate('created_at', today())->count(),
            'logs_this_week' => AuditLog::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'logs_this_month' => AuditLog::whereMonth('created_at', now()->month)->count(),
            'unique_users' => AuditLog::distinct('user_id')->count('user_id'),
            'most_common_events' => AuditLog::selectRaw('event, COUNT(*) as count')
                ->groupBy('event')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            'most_active_tables' => AuditLog::selectRaw('`table`, COUNT(*) as count')
                ->groupBy('table')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
        ];

        return response()->json($stats);
    }

    /**
     * Clean old audit logs
     */
    public function cleanOldLogs(Request $request)
    {
        $request->validate([
            'days_to_keep' => 'required|integer|min:1',
        ]);

        $deletedCount = $this->auditService->cleanOldLogs($request->days_to_keep);

        return response()->json([
            'message' => "Successfully deleted {$deletedCount} old audit logs",
            'deleted_count' => $deletedCount,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuditLogRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AuditLog $auditLog)
    {
        return response()->json($auditLog->load('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AuditLog $auditLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuditLogRequest $request, AuditLog $auditLog)
    {
        // Audit logs should not be updated
        return response()->json(['error' => 'Audit logs cannot be modified'], 403);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AuditLog $auditLog)
    {
        // Audit logs should not be deleted individually
        // Use cleanOldLogs method instead
        return response()->json(['error' => 'Audit logs cannot be deleted individually. Use bulk cleanup instead.'], 403);
    }
}