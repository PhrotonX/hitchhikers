@extends('layouts.app')

@push('head')
    @vite(['resources/css/driver-dashboard.css'])
    @vite(['resources/css/driver_rides.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
<div class="main-layout">
    {{-- Driver Navigation Sidebar --}}
    <aside class="mlay-side">
        <nav class="driver-nav">
            <a href="{{ route('driver.dashboard') }}" class="driver-nav-link">
                <i class="fa-solid fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('driver.earnings') }}" class="driver-nav-link">
                <i class="fa-solid fa-dollar-sign"></i> Earnings
            </a>
            <a href="{{ route('user.view', $user) }}" class="driver-nav-link">
                <i class="fa-solid fa-user-gear"></i> Profile
            </a>
        </nav>
    </aside>

    {{-- Main Content --}}
    <main class="main-content">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Ride Management</h2>
                <a href="{{ route('ride.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> Create New Ride
                </a>
            </div>

            {{-- Ride Filters --}}
            <div class="ride-filters">
                <button class="filter-btn active" onclick="filterRides('all')">All Rides</button>
                <button class="filter-btn" onclick="filterRides('available')">Available</button>
                <button class="filter-btn" onclick="filterRides('ongoing')">Ongoing</button>
                <button class="filter-btn" onclick="filterRides('completed')">Completed</button>
                <button class="filter-btn" onclick="filterRides('cancelled')">Cancelled</button>
            </div>

            {{-- Rides List --}}
            <div class="card-body">
                <div class="rides-list" id="rides-list">
                    @forelse($rides as $ride)
                        <div class="ride-card" data-status="{{ $ride->status }}">
                            <div class="ride-card-header">
                                <div class="ride-card-title">
                                    <h3>{{ $ride->ride_name }}</h3>
                                    <span class="status-badge status-{{ $ride->status }}">
                                        {{ ucfirst($ride->status) }}
                                    </span>
                                </div>
                                <div class="ride-card-actions">
                                    <a href="{{ route('ride.show', $ride) }}" class="btn btn-sm btn-secondary">
                                        <i class="fa-solid fa-eye"></i> View
                                    </a>
                                    @if($ride->status === 'available')
                                        <a href="{{ route('ride.edit', $ride) }}" class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-edit"></i> Edit
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="ride-card-body">
                                <div class="ride-info-grid">
                                    <div class="ride-info-item">
                                        <i class="fa-solid fa-map-pin"></i>
                                        <div>
                                            <span class="label">From</span>
                                            <span class="value">{{ $ride->source_name }}</span>
                                        </div>
                                    </div>
                                    <div class="ride-info-item">
                                        <i class="fa-solid fa-flag-checkered"></i>
                                        <div>
                                            <span class="label">To</span>
                                            <span class="value">{{ $ride->destinations->first()->destination_name ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="ride-info-item">
                                        <i class="fa-solid fa-calendar"></i>
                                        <div>
                                            <span class="label">Departure</span>
                                            <span class="value">{{ $ride->departure_datetime->format('M d, Y g:i A') }}</span>
                                        </div>
                                    </div>
                                    <div class="ride-info-item">
                                        <i class="fa-solid fa-users"></i>
                                        <div>
                                            <span class="label">Capacity</span>
                                            <span class="value">{{ $ride->max_capacity }} seats</span>
                                        </div>
                                    </div>
                                    <div class="ride-info-item">
                                        <i class="fa-solid fa-peso-sign"></i>
                                        <div>
                                            <span class="label">Fare Rate</span>
                                            <span class="value">₱{{ number_format($ride->fare_rate, 2) }}/km</span>
                                        </div>
                                    </div>
                                    @if($ride->vehicle)
                                        <div class="ride-info-item">
                                            <i class="fa-solid fa-car"></i>
                                            <div>
                                                <span class="label">Vehicle</span>
                                                <span class="value">{{ $ride->vehicle->make }} {{ $ride->vehicle->model }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Ride Requests Section --}}
                                @if($ride->status !== 'completed' && $ride->status !== 'cancelled')
                                    <div class="ride-requests-section">
                                        <h4>Ride Requests ({{ $ride->rideRequests->count() }})</h4>
                                        @if($ride->rideRequests->count() > 0)
                                            <div class="requests-list">
                                                @foreach($ride->rideRequests as $request)
                                                    <div class="request-item status-{{ $request->status }}">
                                                        <div class="request-passenger">
                                                            <i class="fa-solid fa-user-circle"></i>
                                                            <span>{{ $request->passenger->getFullName() }}</span>
                                                        </div>
                                                        <div class="request-details">
                                                            <span class="request-price">₱{{ number_format($request->price, 2) }}</span>
                                                            <span class="request-status">{{ ucfirst($request->status) }}</span>
                                                        </div>
                                                        @if($request->status === 'pending')
                                                            <div class="request-actions">
                                                                <button class="btn btn-xs btn-success" onclick="updateRequestStatus({{ $request->id }}, 'approved')">
                                                                    <i class="fa-solid fa-check"></i> Approve
                                                                </button>
                                                                <button class="btn btn-xs btn-danger" onclick="updateRequestStatus({{ $request->id }}, 'declined')">
                                                                    <i class="fa-solid fa-times"></i> Decline
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="no-requests">No ride requests yet.</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="no-rides">
                            <i class="fa-solid fa-car" style="font-size: 4rem; color: var(--text-light);"></i>
                            <p>You haven't created any rides yet.</p>
                            <a href="{{ route('ride.create') }}" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i> Create Your First Ride
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    // Filter rides by status
    function filterRides(status) {
        const rideCards = document.querySelectorAll('.ride-card');
        const filterBtns = document.querySelectorAll('.filter-btn');

        // Update active button
        filterBtns.forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');

        // Filter cards
        rideCards.forEach(card => {
            if (status === 'all') {
                card.style.display = 'block';
            } else {
                if (card.dataset.status === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            }
        });
    }

    // Update request status
    async function updateRequestStatus(requestId, status) {
        try {
            const response = await fetch(`/ride/requests/${requestId}/update-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status })
            });

            if (response.ok) {
                window.location.reload();
            } else {
                alert('Failed to update request status');
            }
        } catch (error) {
            console.error('Error updating request status:', error);
            alert('An error occurred');
        }
    }
</script>
@endpush
