<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ride;
use App\Models\Driver;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class OwnerController extends Controller
{
    /**
     * Show the owner dashboard
     */
    public function dashboard()
    {
        // Check if user has owner permission
        if (!Auth::user()->isPrivileged('owner')) {
            abort(403, 'Access denied. Owner privileges required.');
        }

        $user = Auth::user();

        // Gather statistics
        $stats = [
            'total_users' => User::count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'total_rides' => Ride::count(),
            'active_rides' => Ride::whereIn('status', ['available', 'ongoing'])->count(),
            'total_drivers' => Driver::count(),
            'pending_verifications' => Driver::where('account_status', 'pending')->count(),
            'total_logs' => AuditLog::count(),
            'logs_today' => AuditLog::whereDate('created_at', today())->count(),
            'most_common_events' => AuditLog::selectRaw('event, COUNT(*) as count')
                ->groupBy('event')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
        ];

        // Get recent activity (audit logs)
        $recentActivity = AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Get all privileged users (non-members)
        $privilegedUsers = User::whereIn('user_type', ['owner', 'staff', 'moderator'])
            ->orderByRaw("FIELD(user_type, 'owner', 'staff', 'moderator')")
            ->get();

        return view('pages.owner.dashboard', compact('user', 'stats', 'recentActivity', 'privilegedUsers'));
    }

    /**
     * Update user permission
     */
    public function updateUserPermission(Request $request, User $user): JsonResponse
    {
        // Check if requester has owner permission
        if (!Auth::user()->isPrivileged('owner')) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Owner privileges required.'
            ], 403);
        }

        $request->validate([
            'user_type' => 'required|in:member,moderator,staff,owner'
        ]);

        // Prevent demoting yourself if you're the last owner
        if ($user->id === Auth::id() && $request->user_type !== 'owner') {
            $ownerCount = User::where('user_type', 'owner')->count();
            if ($ownerCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot demote yourself. At least one owner must remain.'
                ], 400);
            }
        }

        $user->user_type = $request->user_type;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User permission updated successfully.'
        ]);
    }

    /**
     * Get system statistics as JSON
     */
    public function statistics(): JsonResponse
    {
        // Check if user has at least staff permission
        if (!Auth::user()->isPrivileged('staff')) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Staff privileges required.'
            ], 403);
        }

        $stats = [
            'users' => [
                'total' => User::count(),
                'new_today' => User::whereDate('created_at', today())->count(),
                'new_this_week' => User::whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
                'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
            ],
            'rides' => [
                'total' => Ride::count(),
                'active' => Ride::whereIn('status', ['available', 'ongoing'])->count(),
                'completed' => Ride::where('status', 'completed')->count(),
                'cancelled' => Ride::where('status', 'cancelled')->count(),
            ],
            'drivers' => [
                'total' => Driver::count(),
                'verified' => Driver::where('account_status', 'active')->count(),
                'pending' => Driver::where('account_status', 'pending')->count(),
            ],
            'audit_logs' => [
                'total' => AuditLog::count(),
                'today' => AuditLog::whereDate('created_at', today())->count(),
                'this_week' => AuditLog::whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count(),
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Show all users page
     */
    public function users()
    {
        if (!Auth::user()->isPrivileged('owner')) {
            abort(403, 'Access denied. Owner privileges required.');
        }

        $users = User::orderBy('created_at', 'desc')
            ->paginate(20);

        return view('pages.owner.users', compact('users'));
    }

    /**
     * Search users for granting permissions (AJAX)
     */
    public function searchUsers(Request $request)
    {
        if (!Auth::user()->isPrivileged('owner')) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied.'
            ], 403);
        }

        $query = $request->get('query', '');
        
        $users = User::where(function($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                  ->orWhere('last_name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->where('user_type', 'member') // Only show members for permission upgrade
            ->limit(10)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->getFullName(),
                    'email' => $user->email,
                    'user_type' => $user->user_type
                ];
            });

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    /**
     * Show audit logs page
     */
    public function auditLogs()
    {
        if (!Auth::user()->isPrivileged('owner')) {
            abort(403, 'Access denied. Owner privileges required.');
        }

        $logs = AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('pages.owner.audit-logs', compact('logs'));
    }
}
