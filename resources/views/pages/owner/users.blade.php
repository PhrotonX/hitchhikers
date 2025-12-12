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
            <a href="{{ route('owner.audit-logs') }}" class="driver-nav-link">
                <i class="fa-solid fa-clipboard-list"></i> Audit Logs
            </a>
            <a href="{{ route('owner.users') }}" class="driver-nav-link active">
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
                <h1><i class="fas fa-users"></i> User Management</h1>
                <p>View and manage all users on the platform.</p>
            </div>

            <div class="content-section">
                @if($users->count() > 0)
                    <table class="audit-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>User Type</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>#{{ $user->id }}</td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 600;">
                                                {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                                            </div>
                                            {{ $user->getFullName() }}
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>
                                        <span class="permission-badge {{ $user->user_type }}">
                                            {{ ucfirst($user->user_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="event-badge {{ $user->account_status ?? 'active' }}">
                                            {{ ucfirst($user->account_status ?? 'active') }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('user.view', $user) }}" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div style="margin-top: 2rem;">
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="no-data">
                        <i class="fas fa-users" style="font-size: 3rem; color: var(--text-light);"></i>
                        <p>No users found.</p>
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection
