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
        .driver-nav-link i { width: 20px; }
        .driver-nav-link:hover {
            background-color: var(--background-hover);
            color: var(--primary);
        }
        .driver-nav-link.active {
            background-color: var(--primary-light);
            color: white;
            border-right: 4px solid var(--accent);
        }
        body.dark-mode .driver-nav-link:hover { color: var(--accent); }
        body.dark-mode .driver-nav-link.active { background-color: var(--primary); }

        /* --- Page Layout --- */
        .ride-management-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            align-items: flex-start;
        }
        
        @media (max-width: 1024px) {
            .ride-management-layout {
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
        body.dark-mode .card-title { color: white; }

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
            width: 100%;
        }
        .btn-primary { background-color: var(--primary); color: white; }
        .btn-primary:hover { background-color: var(--primary-light); }
        .btn-secondary { background-color: var(--border); color: var(--text-dark); }
        .btn-secondary:hover { background-color: #d1d5db; }
        .btn-success { background-color: var(--secondary); color: white; }
        .btn-success:hover { background-color: #059669; }
        .btn-danger { background-color: var(--error); color: white; }
        .btn-danger:hover { background-color: #dc2626; }

        /* --- Active Ride Card --- */
        .map-container {
            width: 100%;
            height: 250px;
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
        .ride-details-list {
            list-style: none;
            padding: 0;
        }
        .ride-details-list li {
            font-size: 1.1rem;
            color: var(--text-light);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .ride-details-list li i {
            color: var(--primary);
            width: 20px;
            text-align: center;
        }
        .ride-details-list strong {
            color: var(--text-dark);
        }
        body.dark-mode .ride-details-list strong { color: white; }
        .card-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
        }
        
        /* "No Active Ride" Placeholder */
        .placeholder-card {
            padding: 40px;
            text-align: center;
            color: var(--text-light);
            border: 2px dashed var(--border);
        }
        body.dark-mode .placeholder-card {
            border-color: #334155;
        }

        /* --- Ride Request Feed --- */
        .ride-request-feed {
            max-height: 800px;
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
        .ride-request-card:hover { box-shadow: var(--shadow-hover); }
        .ride-passenger {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
        }
        body.dark-mode .ride-passenger { color: white; }
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
        .ride-locations li.dropoff i { color: var(--secondary); }
        .ride-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 15px;
        }

        /* --- Cancellation Modal --- */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.active {
            display: flex;
        }
        .modal-content {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        body.dark-mode .modal-content {
            background-color: #1a243d;
        }
        .modal-content h3 {
            margin-top: 0;
            font-size: 1.5rem;
            color: var(--text-dark);
        }
        body.dark-mode .modal-content h3 { color: white; }
        .modal-content label {
            display: block;
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 10px;
        }
        body.dark-mode .modal-content label { color: var(--text-light); }
        .modal-content textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            font-family: inherit;
            font-size: 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            resize: vertical;
        }
        .modal-actions {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

    </style>
@endpush

@section('content')

    <div class="main-layout">

        <aside class="mlay-side">
            <nav class="driver-nav">
                <a href="{{ url('driver/dashboard') }}" class="driver-nav-link">
                    <i class="fa-solid fa-tachometer-alt"></i> {{ __('Dashboard') }}
                </a>
                <a href="{{ url('driver/rides') }}" class="driver-nav-link active">
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

        <main class="main-content">
            
            <div class="ride-management-layout">

                <div class="ride-col-left">
                    
                    <div class="card" id="active-ride-card" style="display: none;">
                        <div class="card-header">
                            <h2 class="card-title">{{ __('Active Ride') }}</h2>
                        </div>
                        <div class="card-body">
                            <div class="map-container" id="map-canvas">
                                {{ __('Map of Active Route') }}
                            </div>
                            
                            <ul class="ride-details-list">
                                <li>
                                    <i class="fa-solid fa-user-circle"></i>
                                    <strong>{{ __('Passenger:') }}</strong> <span id="active-ride-passenger">N/A</span>
                                </li>
                                <li class="pickup">
                                    <i class="fa-solid fa-map-pin"></i>
                                    <strong>{{ __('Pick-up:') }}</strong> <span id="active-ride-pickup">N/A</span>
                                </li>
                                <li class="dropoff">
                                    <i class="fa-solid fa-flag-checkered"></i>
                                    <strong>{{ __('Drop-off:') }}</strong> <span id="active-ride-dropoff">N/A</span>
                                </li>
                            </ul>

                            <div class="card-actions" id="start-ride-buttons">
                                <button class="btn btn-success" id="start-ride-btn"><i class="fa-solid fa-play"></i> {{ __('Start Ride') }}</button>
                                <button class="btn btn-danger" id="cancel-ride-btn-modal"><i class="fa-solid fa-ban"></i> {{ __('Cancel') }}</button>
                            </div>

                            <div class="card-actions" id="end-ride-buttons" style="display: none;">
                                <button class="btn btn-primary" id="complete-ride-btn"><i class="fa-solid fa-check"></i> {{ __('Complete Ride') }}</button>
                            </div>
                        </div>
                    </div>

                    <div class="card placeholder-card" id="no-active-ride-card">
                        <i class="fa-solid fa-car-side" style="font-size: 3rem; margin-bottom: 15px;"></i>
                        <h2 class="card-title">{{ __('No Active Ride') }}</h2>
                        <p>{{ __('Accept a ride from the "Available Rides" list to get started.') }}</p>
                    </div>

                </div>

                <div class="ride-col-right">
                    <div class="card" id="ride-requests-card">
                        <div class="card-header">
                            <h2 class="card-title">{{ __('Available Ride Requests') }}</h2>
                        </div>
                        <div class="card-body ride-request-feed" id="ride-request-feed">
                            
                            @forelse($availableRideRequests as $request)
                                <div class="ride-request-card" data-request-id="{{ $request->id }}">
                                    <div class="ride-passenger">
                                        <i class="fa-solid fa-user-circle"></i>
                                        {{ $request->passenger->name ?? 'Passenger' }} ({{ $request->passenger->getAverageRating() ?? '5.0' }} <i class="fa-solid fa-star" style="color: #F59E0B; font-size: 0.8rem;"></i>)
                                    </div>
                                    <ul class="ride-locations">
                                        <li class="pickup"><i class="fa-solid fa-map-pin"></i> <span>{{ $request->pickup_location }}</span></li>
                                        <li class="dropoff"><i class="fa-solid fa-flag-checkered"></i> <span>{{ $request->dropoff_location }}</span></li>
                                    </ul>
                                    <div class="ride-actions">
                                        <button class="btn btn-success accept-ride-btn" data-request-id="{{ $request->id }}">{{ __('Accept') }} ({{ __('Est.') }} ₱{{ number_format($request->estimated_fare ?? 150, 2) }})</button>
                                        <button class="btn btn-secondary decline-ride-btn" data-request-id="{{ $request->id }}">{{ __('Decline') }}</button>
                                    </div>
                                </div>
                            @empty
                                <p style="text-align: center; padding: 20px;">{{ __('No available ride requests at the moment.') }}</p>
                            @endforelse

                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>

    <!-- Cancellation Modal -->
    <div class="modal-overlay" id="cancel-ride-modal">
        <div class="modal-content">
            <h3>{{ __('Cancel Ride') }}</h3>
            <p>{{ __('Please select a reason for cancellation:') }}</p>
            
            <div class="form-group">
                <label for="cancel-reason">{{ __('Reason:') }}</label>
                <textarea id="cancel-reason" placeholder="{{ __('e.g., Passenger not here, unsafe pickup, etc...') }}"></textarea>
            </div>

            <div class="modal-actions">
                <button class="btn btn-danger" id="cancel-confirm-btn">{{ __('Confirm Cancellation') }}</button>
                <button class="btn btn-secondary" id="cancel-modal-close-btn">{{ __('Go Back') }}</button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Cancel modal functionality
        const openModalBtn = document.getElementById('cancel-ride-btn-modal');
        const closeModalBtn = document.getElementById('cancel-modal-close-btn');
        const modalOverlay = document.getElementById('cancel-ride-modal');

        if (openModalBtn && closeModalBtn && modalOverlay) {
            openModalBtn.addEventListener('click', () => {
                modalOverlay.classList.add('active');
            });
            
            closeModalBtn.addEventListener('click', () => {
                modalOverlay.classList.remove('active');
            });
        }

        // Accept ride
        document.querySelectorAll('.accept-ride-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const requestId = this.dataset.requestId;
                console.log('Accepting ride request: ' + requestId);
                // fetch(`/ride/requests/${requestId}/accept`, { method: 'POST' })
            });
        });

        // Decline ride
        document.querySelectorAll('.decline-ride-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const requestId = this.dataset.requestId;
                console.log('Declining ride request: ' + requestId);
                // fetch(`/ride/requests/${requestId}/decline`, { method: 'POST' })
            });
        });

        // Start ride
        document.getElementById('start-ride-btn')?.addEventListener('click', function() {
            console.log('Starting ride');
            // Switch buttons
            document.getElementById('start-ride-buttons').style.display = 'none';
            document.getElementById('end-ride-buttons').style.display = 'grid';
        });

        // Complete ride
        document.getElementById('complete-ride-btn')?.addEventListener('click', function() {
            console.log('Completing ride');
            // fetch('/driver/rides/complete', { method: 'POST' })
        });

        // Confirm cancellation
        document.getElementById('cancel-confirm-btn')?.addEventListener('click', function() {
            const reason = document.getElementById('cancel-reason').value;
            console.log('Cancelling ride with reason: ' + reason);
            modalOverlay.classList.remove('active');
            // fetch('/driver/rides/cancel', { method: 'POST', body: JSON.stringify({reason}) })
        });
    </script>
@endpush
