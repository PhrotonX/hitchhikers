@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .owner-dashboard {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .dashboard-header {
            margin-bottom: 2rem;
        }

        .dashboard-header h1 {
            font-size: 2rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .dashboard-header p {
            color: var(--text-light);
            font-size: 1.1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
        }

        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-card.users .stat-icon {
            background: rgba(59, 95, 207, 0.1);
            color: var(--primary);
        }

        .stat-card.rides .stat-icon {
            background: rgba(16, 185, 129, 0.1);
            color: var(--secondary);
        }

        .stat-card.drivers .stat-icon {
            background: rgba(245, 158, 11, 0.1);
            color: var(--accent);
        }

        .stat-card.logs .stat-icon {
            background: rgba(239, 68, 68, 0.1);
            color: var(--error);
        }

        .stat-card h3 {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            font-weight: 600;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }

        .stat-card .stat-change {
            font-size: 0.875rem;
            color: var(--secondary);
        }

        .stat-card .stat-change.negative {
            color: var(--error);
        }

        .content-section {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border);
        }

        .section-header h2 {
            font-size: 1.5rem;
            color: var(--text-dark);
            margin: 0;
        }

        .audit-table {
            width: 100%;
            border-collapse: collapse;
        }

        .audit-table thead {
            background: var(--background);
        }

        .audit-table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--text-dark);
            border-bottom: 2px solid var(--border);
        }

        .audit-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
            color: var(--text-light);
        }

        .audit-table tbody tr:hover {
            background: var(--background-hover);
        }

        .event-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .event-badge.created {
            background: rgba(16, 185, 129, 0.1);
            color: var(--secondary);
        }

        .event-badge.updated {
            background: rgba(59, 95, 207, 0.1);
            color: var(--primary);
        }

        .event-badge.deleted {
            background: rgba(239, 68, 68, 0.1);
            color: var(--error);
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-light);
        }

        .btn-secondary {
            background: var(--background);
            color: var(--text-dark);
        }

        .btn-secondary:hover {
            background: var(--border);
        }

        .chart-container {
            height: 300px;
            margin-top: 1rem;
        }

        .no-data {
            text-align: center;
            padding: 3rem;
            color: var(--text-light);
        }

        .user-management-grid {
            display: grid;
            gap: 1rem;
            margin-top: 1rem;
        }

        .user-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: var(--background);
            border-radius: 8px;
            transition: background 0.2s;
        }

        .user-item:hover {
            background: var(--background-hover);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .permission-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .permission-badge.owner {
            background: rgba(245, 158, 11, 0.1);
            color: var(--accent);
        }

        .permission-badge.staff {
            background: rgba(59, 95, 207, 0.1);
            color: var(--primary);
        }

        .permission-badge.moderator {
            background: rgba(16, 185, 129, 0.1);
            color: var(--secondary);
        }

        .permission-badge.member {
            background: rgba(107, 114, 128, 0.1);
            color: var(--text-light);
        }

        body.dark-mode .stat-card,
        body.dark-mode .content-section {
            background-color: #1a243d;
            border: 1px solid #334155;
        }

        body.dark-mode .stat-card h3,
        body.dark-mode .section-header h2 {
            color: white;
        }

        body.dark-mode .stat-card .stat-value {
            color: white;
        }
    </style>
@endpush

@section('content')
<div class="owner-dashboard">
    <div class="dashboard-header">
        <h1>Owner Dashboard</h1>
        <p>Welcome back, {{ $user->getFullName() }}. Here's an overview of your platform.</p>
    </div>

    {{-- Statistics Cards --}}
    <div class="stats-grid">
        <div class="stat-card users">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3>Total Users</h3>
            <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
            <div class="stat-change">+{{ $stats['new_users_today'] }} today</div>
        </div>

        <div class="stat-card rides">
            <div class="stat-icon">
                <i class="fas fa-car"></i>
            </div>
            <h3>Total Rides</h3>
            <div class="stat-value">{{ number_format($stats['total_rides']) }}</div>
            <div class="stat-change">{{ $stats['active_rides'] }} active</div>
        </div>

        <div class="stat-card drivers">
            <div class="stat-icon">
                <i class="fas fa-id-card"></i>
            </div>
            <h3>Verified Drivers</h3>
            <div class="stat-value">{{ number_format($stats['total_drivers']) }}</div>
            <div class="stat-change">{{ $stats['pending_verifications'] }} pending</div>
        </div>

        <div class="stat-card logs">
            <div class="stat-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h3>Audit Logs</h3>
            <div class="stat-value">{{ number_format($stats['total_logs']) }}</div>
            <div class="stat-change">{{ $stats['logs_today'] }} today</div>
        </div>
    </div>

    {{-- Recent Activity (Audit Logs) --}}
    <div class="content-section">
        <div class="section-header">
            <h2><i class="fas fa-history"></i> Recent Activity</h2>
            <a href="/audit-logs" class="btn btn-secondary">View All Logs</a>
        </div>

        @if($recentActivity->count() > 0)
            <table class="audit-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Event</th>
                        <th>Table</th>
                        <th>Record ID</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentActivity as $log)
                        <tr>
                            <td>{{ $log->user ? $log->user->getFullName() : 'System' }}</td>
                            <td>
                                <span class="event-badge {{ $log->event }}">
                                    {{ ucfirst($log->event) }}
                                </span>
                            </td>
                            <td>{{ $log->table }}</td>
                            <td>#{{ $log->record_id }}</td>
                            <td>{{ $log->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                <i class="fas fa-inbox" style="font-size: 3rem; color: var(--text-light);"></i>
                <p>No recent activity to display.</p>
            </div>
        @endif
    </div>

    {{-- User Permission Management --}}
    <div class="content-section">
        <div class="section-header">
            <h2><i class="fas fa-user-shield"></i> User Permission Management</h2>
            <button class="btn btn-primary" onclick="showAddPermissionModal()">
                <i class="fas fa-plus"></i> Grant Permission
            </button>
        </div>

        <div class="user-management-grid">
            @forelse($privilegedUsers as $privilegedUser)
                <div class="user-item">
                    <div class="user-info">
                        <div class="user-avatar">
                            {{ strtoupper(substr($privilegedUser->first_name, 0, 1)) }}{{ strtoupper(substr($privilegedUser->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--text-dark);">
                                {{ $privilegedUser->getFullName() }}
                            </div>
                            <div style="font-size: 0.875rem; color: var(--text-light);">
                                {{ $privilegedUser->email }}
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <span class="permission-badge {{ $privilegedUser->user_type }}">
                            {{ ucfirst($privilegedUser->user_type) }}
                        </span>
                        @if($privilegedUser->user_type !== 'owner' || $user->isPrivileged('owner'))
                            <button class="btn btn-secondary" onclick="changePermission({{ $privilegedUser->id }}, '{{ $privilegedUser->user_type }}')">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="no-data">
                    <p>No privileged users found.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Most Common Events --}}
    <div class="content-section">
        <div class="section-header">
            <h2><i class="fas fa-chart-bar"></i> Most Common Events</h2>
        </div>

        <div class="chart-container">
            <canvas id="eventsChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Most Common Events Chart
    const eventsData = @json($stats['most_common_events']);
    const ctx = document.getElementById('eventsChart');
    
    if (ctx && eventsData.length > 0) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: eventsData.map(e => e.event.charAt(0).toUpperCase() + e.event.slice(1)),
                datasets: [{
                    label: 'Event Count',
                    data: eventsData.map(e => e.count),
                    backgroundColor: 'rgba(59, 95, 207, 0.5)',
                    borderColor: 'rgba(59, 95, 207, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Permission management functions
    function showAddPermissionModal() {
        // TODO: Implement modal to search and add user permissions
        alert('Add permission modal - to be implemented');
    }

    function changePermission(userId, currentType) {
        const newType = prompt(`Change permission for user ${userId}?\nCurrent: ${currentType}\nEnter new type (owner/staff/moderator/member):`);
        
        if (newType && ['owner', 'staff', 'moderator', 'member'].includes(newType.toLowerCase())) {
            fetch(`/owner/users/${userId}/permission`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ user_type: newType.toLowerCase() })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Failed to update permission: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating permission');
            });
        }
    }
</script>
@endpush
