@extends('layouts.dashboard')

@section('content')
<div class="main-layout">
    {{-- Driver Navigation Sidebar --}}
    <aside class="mlay-side">
        <nav class="driver-nav">
            <a href="{{ route('driver.dashboard') }}" class="driver-nav-link active">
                <i class="fa-solid fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('driver.rides') }}" class="driver-nav-link">
                <i class="fa-solid fa-car"></i> Ride Management
            </a>
            <a href="{{ route('driver.earnings') }}" class="driver-nav-link">
                <i class="fa-solid fa-dollar-sign"></i> Earnings
            </a>
            <a href="{{ route('user.view', $user) }}" class="driver-nav-link">
                <i class="fa-solid fa-user-gear"></i> My Profile
            </a>
        </nav>
    </aside>

    {{-- Main Content --}}
    <main class="main-content">
        <div class="dashboard-grid">
            {{-- Left Column --}}
            <div class="dashboard-col-left">
                {{-- Map & Active Ride Card --}}
                <div class="card" id="active-ride-card">
                    <div class="card-header">
                        <h2 class="card-title" id="active-ride-title">
                            @if($activeRide)
                                Active Ride
                            @else
                                Waiting for Rides
                            @endif
                        </h2>
                    </div>
                    <div class="card-body">
                        {{-- Map Container --}}
                        <div class="map-container" id="map-canvas">
                            <div id="map" style="width: 100%; height: 100%;"></div>
                        </div>

                        @if($activeRide)
                        {{-- Active Ride Details --}}
                        <div class="active-ride-details" id="active-ride-details">
                            <ul>
                                <li>
                                    <i class="fa-solid fa-user-circle"></i>
                                    <strong>Passenger:</strong> 
                                    <span id="active-ride-passenger">{{ $activeRide->passenger->getFullName() }}</span>
                                </li>
                                <li class="pickup">
                                    <i class="fa-solid fa-map-pin"></i>
                                    <strong>Pick-up:</strong> 
                                    <span id="active-ride-pickup">{{ $activeRide->source_name }}</span>
                                </li>
                                <li class="dropoff">
                                    <i class="fa-solid fa-flag-checkered"></i>
                                    <strong>Drop-off:</strong> 
                                    <span id="active-ride-dropoff">{{ $activeRide->destinations->first()->destination_name ?? 'N/A' }}</span>
                                </li>
                            </ul>

                            <div class="card-actions">
                                @if($activeRide->status === 'approved')
                                    <button class="btn btn-success" id="start-ride-btn" onclick="updateRideStatus({{ $activeRide->id }}, 'ongoing')">
                                        <i class="fa-solid fa-play"></i> Start Ride
                                    </button>
                                    <button class="btn btn-danger" id="cancel-ride-btn" onclick="updateRideStatus({{ $activeRide->id }}, 'cancelled')">
                                        <i class="fa-solid fa-ban"></i> Cancel
                                    </button>
                                @elseif($activeRide->status === 'ongoing')
                                    <button class="btn btn-primary" id="complete-ride-btn" onclick="updateRideStatus({{ $activeRide->id }}, 'completed')">
                                        <i class="fa-solid fa-check"></i> Complete Ride
                                    </button>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="dashboard-col-right">
                {{-- Driver Overview Card --}}
                <div class="card" id="driver-overview-card">
                    <div class="driver-profile-header">
                        <img src="{{ $user->getProfilePicture() }}" alt="Driver Photo" class="driver-photo" id="driver-photo">
                        <div class="driver-info">
                            <h3 class="driver-name" id="driver-name">{{ $user->getFullName() }}</h3>
                            <span class="status-badge {{ $driverAccount->verification === 'verified' ? 'approved' : 'pending' }}" id="status-badge">
                                <i class="fa-solid fa-{{ $driverAccount->verification === 'verified' ? 'check' : 'clock' }}"></i> 
                                {{ ucfirst($driverAccount->verification) }}
                            </span>
                        </div>
                    </div>
                    <ul class="driver-details-list">
                        @if($vehicle)
                            <li><strong>Vehicle:</strong> <span id="vehicle-info">{{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->manufactured_year }})</span></li>
                        @else
                            <li><strong>Vehicle:</strong> <span id="vehicle-info">No vehicle assigned</span></li>
                        @endif
                        <li><strong>Account Type:</strong> <span>{{ ucfirst(str_replace('_', ' ', $driverAccount->driver_type)) }}</span></li>
                    </ul>
                </div>

                {{-- Available Ride Requests Card --}}
                <div class="card" id="ride-requests-card">
                    <div class="card-header">
                        <h2 class="card-title">Available Ride Requests</h2>
                    </div>
                    <div class="card-body ride-request-feed" id="ride-request-feed">
                        @forelse($rideRequests as $request)
                            <div class="ride-request-card">
                                <div class="ride-passenger">
                                    <i class="fa-solid fa-user-circle"></i>
                                    {{ $request->passenger->getFullName() }}
                                </div>
                                <ul class="ride-locations">
                                    <li class="pickup">
                                        <i class="fa-solid fa-map-pin"></i> 
                                        <span>{{ $request->ride->source_name }}</span>
                                    </li>
                                    <li class="dropoff">
                                        <i class="fa-solid fa-flag-checkered"></i> 
                                        <span>{{ $request->ride->destinations->first()->destination_name ?? 'N/A' }}</span>
                                    </li>
                                </ul>
                                <div class="ride-info-row">
                                    <span class="ride-price">₱{{ number_format($request->price, 2) }}</span>
                                    <span class="ride-time">{{ $request->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="ride-actions">
                                    <button class="btn btn-success" onclick="updateRequestStatus({{ $request->id }}, 'approved')">Accept</button>
                                    <button class="btn btn-secondary" onclick="updateRequestStatus({{ $request->id }}, 'declined')">Decline</button>
                                </div>
                            </div>
                        @empty
                            <p class="no-requests">No ride requests available at the moment.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script type="module">
    import RideMap from '/resources/js/RideMap.js';

    // Initialize map
    const rideMap = new RideMap('map', {
        center: [15.0794, 120.6200], // Default center (Pampanga)
        zoom: 13
    });

    // Update ride status
    window.updateRideStatus = async function(rideId, status) {
        try {
            const response = await fetch(`/ride/${rideId}/update-status`, {
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
                alert('Failed to update ride status');
            }
        } catch (error) {
            console.error('Error updating ride status:', error);
            alert('An error occurred');
        }
    };

    // Update request status
    window.updateRequestStatus = async function(requestId, status) {
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
    };

    // Live updates for ride requests (every 15 seconds)
    setInterval(async () => {
        try {
            const response = await fetch('/api/driver/pending-requests');
            const requests = await response.json();
            
            // Update the feed without full page reload
            const feed = document.getElementById('ride-request-feed');
            if (feed && requests.length > 0) {
                // Only update if there are new requests
                const currentCount = feed.querySelectorAll('.ride-request-card').length;
                if (requests.length !== currentCount) {
                    window.location.reload();
                }
            }
        } catch (error) {
            console.error('Error fetching ride requests:', error);
        }
    }, 15000);
</script>
@endpush
