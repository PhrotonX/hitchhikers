@extends('layouts.app')

@push('head')
    @vite(['resources/css/owner-dashboard.css'])
    @vite(['resources/css/driver-dashboard.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="main-layout">
    {{-- Owner Navigation Sidebar --}}
    <aside class="mlay-side">
        <nav class="driver-nav">
            <a href="{{ route('owner.dashboard') }}" class="driver-nav-link active">
                <i class="fa-solid fa-chart-line"></i> Statistics
            </a>
            <a href="#" class="driver-nav-link">
                <i class="fa-solid fa-clipboard-list"></i> Audit Logs
            </a>
            <a href="#" class="driver-nav-link">
                <i class="fa-solid fa-users"></i> Users
            </a>
            <a href="{{ route('user.view', $user) }}" class="driver-nav-link">
                <i class="fa-solid fa-user-gear"></i> Profile
            </a>
        </nav>
    </aside>

    {{-- Main Content --}}
    <main class="main-content">
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

        </div> <!-- End owner-dashboard -->
    </main> <!-- End main-content -->
</div> <!-- End main-layout -->
@endsection
