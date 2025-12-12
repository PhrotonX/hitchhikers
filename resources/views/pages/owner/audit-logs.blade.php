@extends('layouts.app')

@push('head')
    @vite(['resources/css/owner-dashboard.css'])
    @vite(['resources/css/driver-dashboard.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
<div class="main-layout">
    {{-- Owner Navigation Sidebar --}}
    <aside class="mlay-side">
        <nav class="driver-nav">
            <a href="{{ route('owner.dashboard') }}" class="driver-nav-link">
                <i class="fa-solid fa-chart-line"></i> Statistics
            </a>
            <a href="{{ route('owner.audit-logs') }}" class="driver-nav-link active">
                <i class="fa-solid fa-clipboard-list"></i> Audit Logs
            </a>
            <a href="{{ route('owner.users') }}" class="driver-nav-link">
                <i class="fa-solid fa-users"></i> Users
            </a>
            <a href="{{ route('user.view', Auth::user()) }}" class="driver-nav-link">
                <i class="fa-solid fa-user-gear"></i> Profile
            </a>
        </nav>
    </aside>

    {{-- Main Content --}}
    <main class="main-content">
        <div class="owner-dashboard">
            <div class="dashboard-header">
                <h1><i class="fas fa-clipboard-list"></i> Audit Logs</h1>
                <p>Complete system activity log with user actions and events.</p>
            </div>

            <div class="content-section">
                @if($logs->count() > 0)
                    <table class="audit-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Event</th>
                                <th>Table</th>
                                <th>Record ID</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>#{{ $log->id }}</td>
                                    <td>{{ $log->user ? $log->user->getFullName() : 'System' }}</td>
                                    <td>
                                        <span class="event-badge {{ $log->event }}">
                                            {{ ucfirst($log->event) }}
                                        </span>
                                    </td>
                                    <td>{{ $log->table }}</td>
                                    <td>#{{ $log->record_id }}</td>
                                    <td>{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div style="margin-top: 2rem;">
                        {{ $logs->links() }}
                    </div>
                @else
                    <div class="no-data">
                        <i class="fas fa-inbox" style="font-size: 3rem; color: var(--text-light);"></i>
                        <p>No audit logs found.</p>
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection
