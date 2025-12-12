@extends('layouts.app')

<x-map-head/>

@push('head')
    @vite(['resources/css/driver-dashboard.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
<div class="main-layout container">
    <aside class="mlay-side">
        @auth
            @if (Auth::user()->isPrivileged('owner'))
                <nav class="driver-nav">
                    <a href="{{ route('owner.dashboard') }}" class="driver-nav-link">
                        <i class="fa-solid fa-chart-line"></i> Statistics
                    </a>
                    <a href="#" class="driver-nav-link">
                        <i class="fa-solid fa-clipboard-list"></i> Audit Logs
                    </a>
                    <a href="#" class="driver-nav-link">
                        <i class="fa-solid fa-users"></i> Users
                    </a>
                    <a href="{{ route('user.view', Auth::user()) }}" class="driver-nav-link">
                        <i class="fa-solid fa-user-gear"></i> Profile
                    </a>
                </nav>
            @elseif (Auth::user()->isDriver())
                <nav class="driver-nav">
                    <a href="{{ route('driver.dashboard') }}" class="driver-nav-link">
                        <i class="fa-solid fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="{{ route('driver.earnings') }}" class="driver-nav-link">
                        <i class="fa-solid fa-dollar-sign"></i> Earnings
                    </a>
                    <a href="{{ route('user.view', Auth::user()) }}" class="driver-nav-link">
                        <i class="fa-solid fa-user-gear"></i> Profile
                    </a>
                </nav>
            @else
                <nav class="driver-nav">
                    <a href="{{ route('home') }}" class="driver-nav-link">
                        <i class="fa-solid fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="/ride/requests/created" class="driver-nav-link">
                        <i class="fa-solid fa-car"></i> My Ride Requests
                    </a>
                    <a href="{{ route('user.view', Auth::user()) }}" class="driver-nav-link">
                        <i class="fa-solid fa-user-gear"></i> Profile
                    </a>
                </nav>
            @endif
        @endauth
    </aside>

    <main class="main-content">
    @if(session('success'))
        <div style="background: #d4edda; border: 1px solid #c3e6cb; border-radius: 6px; padding: 15px; margin-bottom: 20px; color: #155724;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="page-header">
        <h1><i class="fas fa-route"></i> {{ $ride->ride_name }}</h1>
        <div style="margin-top: 10px;">
            <button onclick="history.back()" class="btn btn-secondary" style="margin-right: 10px;">
                <i class="fas fa-arrow-left"></i> {{ __('string.back') }}
            </button>
            @can('update', $ride)
                <a href="{{ route('ride.edit', $ride->id) }}" class="btn btn-primary" style="margin-right: 10px;">
                    <i class="fas fa-edit"></i> {{ __('string.edit_ride') }}
                </a>
            @endcan
            @can('delete', $ride)
                <a href="{{ route('ride.delete', $ride->id) }}" class="btn btn-danger">
                    <i class="fas fa-trash"></i> {{ __('string.delete_ride') }}
                </a>
            @endcan
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-info-circle"></i> {{ __('ride.ride_details') }}</h2>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div>
                    <strong><i class="fas fa-circle-info"></i> {{ __('ride.status') }}:</strong>
                    <p style="margin: 5px 0 0 0; font-size: 16px;">
                        @if($ride->status == 'active')
                            <span style="color: #28a745;"><i class="fas fa-check-circle"></i> {{ $ride->status }}</span>
                        @else
                            <span>{{ $ride->status ?: __('string.not_set') }}</span>
                        @endif
                    </p>
                </div>
                <div>
                    <strong><i class="fas fa-money-bill-wave"></i> {{ __('ride.fare_rate') }}:</strong>
                    <p style="margin: 5px 0 0 0; font-size: 16px;">₱{{ number_format($ride->fare_rate, 2) }}</p>
                </div>
                @if(isset($ride->minimum_fare))
                    <div>
                        <strong><i class="fas fa-coins"></i> {{ __('ride.minimum_fare') }}:</strong>
                        <p style="margin: 5px 0 0 0; font-size: 16px;">₱{{ number_format($ride->minimum_fare, 2) }}</p>
                    </div>
                @endif
                <div>
                    <strong><i class="fas fa-calendar-plus"></i> {{ __('string.created_at') }}:</strong>
                    <p style="margin: 5px 0 0 0; font-size: 16px;">{{ $ride->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <strong><i class="fas fa-calendar-check"></i> {{ __('string.updated_at') }}:</strong>
                    <p style="margin: 5px 0 0 0; font-size: 16px;">{{ $ride->updated_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-user-tie"></i> {{ __('driver.driver') }}</h2>
        </div>
        <div class="card-body">
            @if($ride->driver)
                @php
                    $driverUser = $ride->driver->user;
                    $profilePicture = $driverUser ? $driverUser->getProfilePicture() : null;
                    $profilePicUrl = $profilePicture && $profilePicture->getPath() 
                        ? asset('storage/' . $profilePicture->getPath()) 
                        : asset('storage/default-avatar.png');
                @endphp
                <div style="display: flex; gap: 20px; align-items: center;">
                    <img src="{{$profilePicUrl}}" alt="Driver" 
                         style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #e5e7eb;">
                    <div style="flex-grow: 1;">
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                            <div>
                                <strong><i class="fas fa-id-card"></i> {{ __('driver.driver_name') }}:</strong>
                                <p style="margin: 5px 0 0 0; font-size: 16px;">{{ $ride->driver->driver_account_name }}</p>
                            </div>
                            <div>
                                <strong><i class="fas fa-building"></i> {{ __('driver.company') }}:</strong>
                                <p style="margin: 5px 0 0 0; font-size: 16px;">{{ $ride->driver->company ?: __('string.independent') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <p style="color: #666;"><i class="fas fa-exclamation-circle"></i> {{ __('driver.driver_not_found') }}</p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-map-marked-alt"></i> {{ __('string.destinations') }}</h2>
        </div>
        <div class="card-body">
            @if($destinations && $destinations->count() > 0)
                <div id="map" style="height: 400px; width: 100%; margin-bottom: 20px; border-radius: 8px; border: 1px solid #ddd;"></div>
                
                <div class="destinations-list">
                    @foreach($destinations as $index => $destination)
                        <div class="destination-item" data-index="{{ $index }}" data-lat="{{ $destination->latitude }}" data-lng="{{ $destination->longitude }}" data-destination-id="{{ $destination->id }}" style="padding: 15px; margin: 10px 0; border-left: 4px solid transparent; background: #f8f9fa; border-radius: 6px; transition: all 0.3s ease;">
                            <p style="margin: 0 0 8px 0; font-weight: bold; font-size: 16px;">
                                <i class="fas fa-map-pin" style="color: #dc3545;"></i> {{$index + 1}}. <span class="destination-name">{{ __('string.loading') }}...</span>
                            </p>
                            <p style="margin: 0; color: #666; font-size: 14px;">
                                <i class="fas fa-location-dot"></i> {{ __('string.coordinates') }}: {{ $destination->latitude }}, {{ $destination->longitude }}
                            </p>
                        </div>
                    @endforeach
                </div>

                <style>
                    .destination-item.highlighted {
                        background-color: #e3f2fd !important;
                        border-left-color: #2196f3 !important;
                    }
                </style>

                <script type="module">
                    import RideMap from '{{ Vite::asset("resources/js/RideMap.js") }}';

                    var map = new RideMap('map', '{{env("NOMINATIM_URL", "")}}', '{{env("APP_URL", "")}}', {
                        @auth
                            'is_auth': true,
                            @if (Auth::user()->isDriver())
                                'is_driver': true
                            @endif
                        @endauth
                    });
                    
                    map.configureMarkerIcon('default', '{{Vite::asset("resources/img/red_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
                    
                    // Disable auto-pan to current location
                    map.panToCurrentPos = false;
                    
                    // Set up marker click handler to show address and highlight destination item
                    map.setOnRideMarkerClick((e, destination) => {
                        // Remove previous highlights
                        document.querySelectorAll('.destination-item').forEach(item => {
                            item.classList.remove('highlighted');
                        });

                        // Highlight the corresponding destination item
                        const destItem = document.querySelector(`.destination-item[data-destination-id="${destination.id}"]`);
                        if (destItem) {
                            destItem.classList.add('highlighted');
                            destItem.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }

                        // Fetch and display address above the marker
                        fetch("{{env('NOMINATIM_URL', '')}}/reverse?lat=" + destination.latitude + "&lon=" + destination.longitude + '&format=json&zoom=18&addressdetails=1')
                            .then(response => response.json())
                            .then(data => {
                                e.target.bindTooltip(data.display_name, {
                                    permanent: true,
                                    direction: 'top',
                                    className: 'destination-tooltip'
                                }).openTooltip();
                            })
                            .catch(error => {
                                console.log("Error: " + error);
                                e.target.bindTooltip('{{ __('string.location') }}', {
                                    permanent: true,
                                    direction: 'top'
                                }).openTooltip();
                            });
                    });
                    
                    // Load ride markers with line and auto-fit bounds
                    map.retrieveRideMarkers({{ $ride->id }}, true, true)();

                    // Reverse geocode each destination
                    document.querySelectorAll('.destination-item').forEach(function(item) {
                        var lat = item.getAttribute('data-lat');
                        var lng = item.getAttribute('data-lng');
                        var nameElement = item.querySelector('.destination-name');

                        fetch("{{env('NOMINATIM_URL', '')}}/reverse?lat=" + lat + "&lon=" + lng + '&format=json&zoom=18&addressdetails=1')
                            .then(response => response.json())
                            .then(data => {
                                nameElement.textContent = data.display_name;
                            })
                            .catch(error => {
                                console.log("Error: " + error);
                                nameElement.textContent = '{{ __('string.location') }}';
                            });
                    });
                </script>
            @else
                <p style="color: #666;"><i class="fas fa-info-circle"></i> {{ __('string.no_destinations') }}</p>
            @endif
        </div>
    </div>
    </main>
</div>
@endsection