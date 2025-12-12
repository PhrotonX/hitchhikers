@extends('layouts.app')

@push('head')
    <style>
        /* --- Driver Sidebar Navigation --- */
        .driver-nav {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin: -10px;
        }
        .driver-nav-link {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 20px;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            color: var(--text-light);
            transition: all 0.2s ease-in-out;
        }
        .driver-nav-link i {
            width: 20px;
        }
        .driver-nav-link:hover {
            background-color: var(--background-hover);
            color: var(--primary);
        }
        .driver-nav-link.active {
            background-color: var(--primary-light);
            color: white;
            border-right: 4px solid var(--accent);
        }
        body.dark-mode .driver-nav-link:hover {
            color: var(--accent);
        }
        body.dark-mode .driver-nav-link.active {
            background-color: var(--primary);
            color: white;
        }

        /* --- Dashboard Grid Layout --- */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }
        
        @media (max-width: 1024px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        /* --- Generic Card Style --- */
        .card {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 24px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }
        body.dark-mode .card {
            background-color: #1a243d;
            border: 1px solid #334155;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }
        body.dark-mode .card-title {
            color: white;
        }

        /* --- Map & Active Ride Card --- */
        .map-container {
            width: 100%;
            height: 400px;
            background-color: #e0e0e0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-weight: 600;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
        .active-ride-details ul {
            list-style: none;
            padding: 0;
        }
        .active-ride-details li {
            font-size: 1.1rem;
            color: var(--text-light);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .active-ride-details li i {
            color: var(--primary);
        }
        .active-ride-details strong {
            color: var(--text-dark);
        }
        body.dark-mode .active-ride-details strong {
            color: white;
        }
        .card-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
        }

        /* --- Ride Request Feed --- */
        .ride-request-feed {
            max-height: 400px;
            overflow-y: auto;
            padding-right: 10px;
        }
        .ride-request-card {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: box-shadow 0.2s;
        }
        .ride-request-card:hover {
            box-shadow: var(--shadow-hover);
        }
        .ride-passenger {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
        }
        body.dark-mode .ride-passenger {
            color: white;
        }
        .ride-locations {
            list-style: none;
            padding: 10px 0 0 35px;
            font-size: 0.9rem;
        }
        .ride-locations li {
            position: relative;
            padding-left: 20px;
            color: var(--text-light);
            margin-bottom: 5px;
        }
        .ride-locations li i {
            position: absolute;
            left: 0;
            top: 4px;
            color: var(--primary);
        }
        .ride-locations li.dropoff i {
            color: var(--secondary);
        }
        .ride-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 15px;
        }

        /* --- Driver Overview --- */
        .driver-profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .driver-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--border);
        }
        .driver-name {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        .status-badge.approved {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-badge.pending {
            background-color: #feF3c7;
            color: #92400e;
        }
        .status-badge.rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .driver-details-list {
            list-style: none;
            padding: 0;
            margin-top: 15px;
        }
        .driver-details-list li {
            font-size: 1rem;
            color: var(--text-light);
            padding: 5px 0;
        }
        .driver-details-list strong {
            color: var(--text-dark);
        }
        body.dark-mode .driver-name,
        body.dark-mode .driver-details-list strong {
            color: white;
        }
        body.dark-mode .driver-photo {
            border-color: #334155;
        }

        /* --- Button Styles --- */
        .btn {
            padding: 12px 15px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        .btn-primary:hover {
            background-color: var(--primary-light);
        }
        .btn-secondary {
            background-color: var(--border);
            color: var(--text-dark);
        }
        .btn-secondary:hover {
            background-color: #d1d5db;
        }
        .btn-success {
            background-color: var(--secondary);
            color: white;
        }
        .btn-success:hover {
            background-color: #059669;
        }
        .btn-danger {
            background-color: var(--error);
            color: white;
        }
        .btn-danger:hover {
            background-color: #dc2626;
        }
    </style>
@endpush

@section('content')
    <div class="main-layout">

        <!--DRIVER NAVIGATION-->
        <aside class="mlay-side">
            <nav class="driver-nav">
                <a href="{{ url('driver/dashboard') }}" class="driver-nav-link active">
                    <i class="fa-solid fa-tachometer-alt"></i> {{ __('Dashboard') }}
                </a>
                <a href="{{ url('driver/rides') }}" class="driver-nav-link">
                    <i class="fa-solid fa-car"></i> {{ __('Ride Management') }}
                </a>
                <a href="{{ url('driver/earnings') }}" class="driver-nav-link">
                    <i class="fa-solid fa-dollar-sign"></i> {{ __('Earnings') }}
                </a>
                <a href="{{ url('driver/profile') }}" class="driver-nav-link">
                    <i class="fa-solid fa-user-gear"></i> {{ __('My Profile') }}
                </a>
                <a href="{{ url('driver/notifications') }}" class="driver-nav-link">
                    <i class="fa-solid fa-bell"></i> {{ __('Notifications') }}
                </a>
            </nav>
        </aside>

        <!--MAIN CONTENT-->
        <main class="main-content">
            
            <div class="dashboard-grid">

                <!-- Left Column -->
                <div class="dashboard-col-left">
                    
                    <!-- MAP & ACTIVE RIDE -->
                    <div class="card" id="active-ride-card">
                        <div class="card-header">
                            <h2 class="card-title" id="active-ride-title">
                                @if(Auth::user()->isOnRide())
                                    {{ __('Active Ride') }}
                                @else
                                    {{ __('Offline - Waiting for Rides') }}
                                @endif
                            </h2>
                        </div>
                        <div class="card-body">
                            <!-- Map Placeholder -->
                            <div class="map-container" id="map-canvas">
                                {{ __('Map Canvas') }}
                            </div>
                            
                            <!-- Active Ride Details -->
                            @if(Auth::user()->isOnRide())
                                <div class="active-ride-details" id="active-ride-details">
                                    <ul>
                                        <li>
                                            <i class="fa-solid fa-user-circle"></i>
                                            <strong>{{ __('Passenger:') }}</strong> 
                                            <span id="active-ride-passenger">{{ Auth::user()->currentRide->passenger->name ?? 'N/A' }}</span>
                                        </li>
                                        <li class="pickup">
                                            <i class="fa-solid fa-map-pin"></i>
                                            <strong>{{ __('Pick-up:') }}</strong> 
                                            <span id="active-ride-pickup">{{ Auth::user()->currentRide->pickup_location ?? 'N/A' }}</span>
                                        </li>
                                        <li class="dropoff">
                                            <i class="fa-solid fa-flag-checkered"></i>
                                            <strong>{{ __('Drop-off:') }}</strong> 
                                            <span id="active-ride-dropoff">{{ Auth::user()->currentRide->dropoff_location ?? 'N/A' }}</span>
                                        </li>
                                    </ul>

                                    <div class="card-actions">
                                        <button class="btn btn-success" id="start-ride-btn"><i class="fa-solid fa-play"></i> {{ __('Start Ride') }}</button>
                                        <button class="btn btn-danger" id="cancel-ride-btn"><i class="fa-solid fa-ban"></i> {{ __('Cancel') }}</button>
                                        <button class="btn btn-primary" id="complete-ride-btn" style="display: none;"><i class="fa-solid fa-check"></i> {{ __('Complete Ride') }}</button>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>

                <!-- Right Column -->
                <div class="dashboard-col-right">

                    <!-- DRIVER OVERVIEW -->
                    <div class="card" id="driver-overview-card">
                        <div class="driver-profile-header">
                            <img src="{{ Auth::user()->profile_photo_url ?? asset('images/placeholder_profile.png') }}" alt="Driver Photo" class="driver-photo" id="driver-photo">
                            <div class="driver-info">
                                <h3 class="driver-name" id="driver-name">{{ Auth::user()->name }}</h3>
                                <span class="status-badge approved" id="status-badge">
                                    <i class="fa-solid fa-check"></i> 
                                    @if(Auth::user()->getDriverAccount())
                                        {{ ucfirst(Auth::user()->getDriverAccount()->status) }}
                                    @else
                                        {{ __('No Driver Account') }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <ul class="driver-details-list">
                            @if(Auth::user()->getDriverAccount() && Auth::user()->getDriverAccount()->vehicle)
                                <li><strong>{{ __('Vehicle:') }}</strong> <span id="vehicle-info">{{ Auth::user()->getDriverAccount()->vehicle->make }} {{ Auth::user()->getDriverAccount()->vehicle->model }}</span></li>
                            @endif
                            <li><strong>{{ __('Rating:') }}</strong> <span id="driver-rating"><i class="fa-solid fa-star" style="color: #F59E0B;"></i> {{ Auth::user()->getAverageRating() ?? '0' }}</span></li>
                        </ul>
                    </div>

                    <!-- AVAILABLE RIDE REQUESTS -->
                    <div class="card" id="ride-requests-card">
                        <div class="card-header">
                            <h2 class="card-title">{{ __('Available Rides') }}</h2>
                        </div>
                        <div class="card-body ride-request-feed" id="ride-request-feed">
                            
                            @if($availableRideRequests && $availableRideRequests->count() > 0)
                                @foreach($availableRideRequests as $request)
                                    <div class="ride-request-card">
                                        <div class="ride-passenger">
                                            <i class="fa-solid fa-user-circle"></i>
                                            {{ $request->passenger->name ?? 'Passenger' }}
                                        </div>
                                        <ul class="ride-locations">
                                            <li class="pickup"><i class="fa-solid fa-map-pin"></i> <span>{{ $request->pickup_location }}</span></li>
                                            <li class="dropoff"><i class="fa-solid fa-flag-checkered"></i> <span>{{ $request->dropoff_location }}</span></li>
                                        </ul>
                                        <div class="ride-actions">
                                            <button class="btn btn-success accept-ride-btn" data-request-id="{{ $request->id }}">{{ __('Accept') }}</button>
                                            <button class="btn btn-secondary decline-ride-btn" data-request-id="{{ $request->id }}">{{ __('Decline') }}</button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p>{{ __('No available ride requests at the moment.') }}</p>
                            @endif
                            
                        </div>
                    </div>

                </div>

            </div>

        </main>
    </div>
@endsection

@push('scripts')
    <script>
        // Handle ride acceptance
        document.querySelectorAll('.accept-ride-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const requestId = this.dataset.requestId;
                // Make AJAX call to accept ride
                console.log('Accepting ride request: ' + requestId);
                // fetch(`/ride/requests/${requestId}/accept`, { method: 'POST' })
            });
        });

        // Handle ride decline
        document.querySelectorAll('.decline-ride-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const requestId = this.dataset.requestId;
                // Make AJAX call to decline ride
                console.log('Declining ride request: ' + requestId);
                // fetch(`/ride/requests/${requestId}/decline`, { method: 'POST' })
            });
        });

        // Handle start ride
        document.getElementById('start-ride-btn')?.addEventListener('click', function() {
            console.log('Starting ride');
            // Make AJAX call to start ride
        });

        // Handle cancel ride
        document.getElementById('cancel-ride-btn')?.addEventListener('click', function() {
            console.log('Cancelling ride');
            // Make AJAX call to cancel ride
        });

        // Handle complete ride
        document.getElementById('complete-ride-btn')?.addEventListener('click', function() {
            console.log('Completing ride');
            // Make AJAX call to complete ride
        });
    </script>
@endpush
