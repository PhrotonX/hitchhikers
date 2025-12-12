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
                                <th>Data</th>
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
                                    <td>#{{ $log->data_id }}</td>
                                    <td>
                                        @if($log->event === 'created' && $log->new_values)
                                            <button class="btn btn-sm btn-secondary" onclick="showDataModal({{ json_encode($log->new_values) }}, 'Created Data')">
                                                <i class="fas fa-plus-circle"></i> View Created
                                            </button>
                                        @elseif($log->event === 'updated' && ($log->old_values || $log->new_values))
                                            <button class="btn btn-sm btn-secondary" onclick="showChangesModal({{ json_encode($log->old_values ?? []) }}, {{ json_encode($log->new_values ?? []) }})">
                                                <i class="fas fa-exchange-alt"></i> View Changes
                                            </button>
                                        @elseif($log->event === 'deleted' && $log->old_values)
                                            <button class="btn btn-sm btn-secondary" onclick="showDataModal({{ json_encode($log->old_values) }}, 'Deleted Data')">
                                                <i class="fas fa-trash-alt"></i> View Deleted
                                            </button>
                                        @else
                                            <span style="color: var(--text-light); font-size: 0.875rem;">No data</span>
                                        @endif
                                    </td>
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

            {{-- Data Modal --}}
            <div id="dataModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 id="dataModalTitle"><i class="fas fa-database"></i> Record Data</h3>
                        <button class="modal-close" onclick="closeDataModal()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="dataModalContent" style="max-height: 500px; overflow-y: auto;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
    function showDataModal(data, title) {
        document.getElementById('dataModalTitle').innerHTML = `<i class="fas fa-database"></i> ${title}`;
        
        let html = '<table class="audit-table" style="margin: 0;">';
        html += '<thead><tr><th>Field</th><th>Value</th></tr></thead><tbody>';
        
        for (const [key, value] of Object.entries(data)) {
            if (key !== 'password' && key !== 'remember_token') {
                const displayValue = formatValue(value);
                html += `<tr>
                    <td style="font-weight: 600;">${formatFieldName(key)}</td>
                    <td>${displayValue}</td>
                </tr>`;
            }
        }
        
        html += '</tbody></table>';
        document.getElementById('dataModalContent').innerHTML = html;
        document.getElementById('dataModal').style.display = 'flex';
    }

    function showChangesModal(oldData, newData) {
        document.getElementById('dataModalTitle').innerHTML = '<i class="fas fa-exchange-alt"></i> Changes Made';
        
        let html = '<table class="audit-table" style="margin: 0;">';
        html += '<thead><tr><th>Field</th><th>Old Value</th><th>New Value</th></tr></thead><tbody>';
        
        const allKeys = new Set([...Object.keys(oldData || {}), ...Object.keys(newData || {})]);
        
        for (const key of allKeys) {
            if (key !== 'password' && key !== 'remember_token' && key !== 'updated_at') {
                const oldValue = oldData?.[key];
                const newValue = newData?.[key];
                
                if (oldValue !== newValue) {
                    html += `<tr>
                        <td style="font-weight: 600;">${formatFieldName(key)}</td>
                        <td style="color: #dc2626;">${formatValue(oldValue)}</td>
                        <td style="color: #16a34a;">${formatValue(newValue)}</td>
                    </tr>`;
                }
            }
        }
        
        html += '</tbody></table>';
        document.getElementById('dataModalContent').innerHTML = html;
        document.getElementById('dataModal').style.display = 'flex';
    }

    function closeDataModal() {
        document.getElementById('dataModal').style.display = 'none';
    }

    function formatFieldName(field) {
        return field.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
    }

    function formatValue(value) {
        if (value === null || value === undefined) {
            return '<span style="color: var(--text-light); font-style: italic;">null</span>';
        }
        if (typeof value === 'boolean') {
            return value ? '<span style="color: #16a34a;">✓ Yes</span>' : '<span style="color: #dc2626;">✗ No</span>';
        }
        if (typeof value === 'object') {
            return '<code style="background: var(--bg-light); padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.875rem;">' + JSON.stringify(value) + '</code>';
        }
        return String(value);
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('dataModal');
        if (event.target === modal) {
            closeDataModal();
        }
    }
</script>
@endpush
@endsection
