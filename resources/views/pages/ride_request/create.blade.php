@extends('layouts.app')

<x-map-head/>

@push('head')
    @vite(['resources/css/driver-dashboard.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite('resources/js/RideMap.js');
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
                    <a href="/ride/requests/created" class="driver-nav-link active">
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
        <div class="page-header">
            <h1><i class="fas fa-map-marker-alt"></i> Make a Ride Request</h1>
            <button id="ride-request-back" class="btn btn-secondary" onclick="history.back()">
                <i class="fas fa-arrow-left"></i> Back
            </button>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-route"></i> Ride Information</h2>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <strong><i class="fas fa-car-side"></i> Ride Name:</strong>
                        <p id="ride-request-destination" style="margin: 5px 0 0 0; font-size: 16px;">{{$ride->ride_name}}</p>
                    </div>
                    <div>
                        <strong><i class="fas fa-info-circle"></i> Description:</strong>
                        <p id="ride-request-description" style="margin: 5px 0 0 0; font-size: 16px;">{{$ride->description}}</p>
                    </div>
                </div>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                    <p style="margin: 0; color: #1e3a8a;"><i class="fas fa-location-dot"></i> <strong>Click on the map below to choose your pickup location</strong></p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-map"></i> Select Pickup Location</h2>
            </div>
            <div class="card-body">
                <div id="map" style="height: 400px; border-radius: 8px; border: 2px solid #e5e7eb;"></div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                        <strong><i class="fas fa-location-crosshairs"></i> From (Your Location):</strong>
                        <p id="ride-request-from-label" style="margin: 8px 0 0 0; color: #666;">Retrieving location...</p>
                    </div>
                    <div style="background: #e3f2fd; padding: 15px; border-radius: 8px;">
                        <strong><i class="fas fa-map-pin"></i> To (Pickup Location):</strong>
                        <p id="ride-request-to-label" style="margin: 8px 0 0 0; color: #666;">Click on map to select</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-edit"></i> Request Details</h2>
            </div>
            <div class="card-body">
                <form action="/ride/requests/create/submit" method="POST">
                    @csrf
                    
                    <input type="number" name="ride_id" id="ride-request-ride-id" value="{{$ride->id}}" hidden>
                    <input type="number" name="destination_id" id="ride-request-destination-id" hidden value="{{old('destination_id')}}">
                    <input type="text" name="from_latitude" id="ride-request-from-latitude" hidden value="{{old('from_latitude')}}">
                    <input type="text" name="from_longitude" id="ride-request-from-longitude" hidden value="{{old('from_longitude')}}">
                    <input type="text" name="to_latitude" id="ride-request-to-latitude" hidden value="{{old('to_latitude')}}">
                    <input type="text" name="to_longitude" id="ride-request-to-longitude" hidden value="{{old('to_longitude')}}">
                    <input type="text" name="price" id="ride-request-price" hidden value="{{old('price')}}">

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;"><i class="fas fa-map-marker"></i> Pickup Location / Address *</label>
                        <input type="text" name="pickup_at" id="ride-request-pickup-at" required value="{{old('pickup_at')}}" 
                               style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 16px;"
                               placeholder="Enter pickup address">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;"><i class="fas fa-money-bill-wave"></i> Estimated Price: ₱<span id="price-value">0.00</span></label>
                        <p style="color: #666; font-size: 14px; margin: 0;">Price calculated based on distance and fare rate</p>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;"><i class="fas fa-clock"></i> Pickup Time *</label>
                        <input type="time" name="time" id="ride-request-time" required value="{{old('time')}}"
                               style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 16px;">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-weight: 600;"><i class="fas fa-message"></i> Message (Optional)</label>
                        <textarea name="message" id="ride-request-message" rows="4"
                                  style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 16px; resize: vertical;"
                                  placeholder="Add any additional notes for the driver...">{{old('message')}}</textarea>
                    </div>

                    @if ($errors->any())
                        <div style="background: #fee2e2; border: 1px solid #ef4444; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                            <strong style="color: #991b1b;"><i class="fas fa-exclamation-circle"></i> Errors:</strong>
                            <ul style="margin: 8px 0 0 20px; color: #991b1b;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 18px;">
                        <i class="fas fa-paper-plane"></i> {{__('string.submit')}} Request
                    </button>
                </form>
            </div>
        </div>
    </main>
</div>

    <script>
        window.fare_rate = {{ $ride->fare_rate ?? 0 }};
        window.minimum_rate = {{ $ride->minimum_rate ?? 0 }};
    </script>
    <script type="module">
        import RideMap from '{{ Vite::asset("resources/js/RideMap.js") }}';
        import CreateRideRequestPage from '{{ Vite::asset("resources/js/CreateRideRequestPage.js") }}';

        var map = new RideMap('map', '{{env("NOMINATIM_URL", "")}}', '{{env("APP_URL", "")}}', {
            @auth
                'is_auth': true,
                @if (Auth::user()->isDriver())
                    'is_driver': true
                @endif
            @endauth
        });

        map.configureMarkerIcon('default', '{{Vite::asset("resources/img/red_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        map.configureMarkerIcon('currentPos', '{{Vite::asset("resources/img/current_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        map.configureMarkerIcon('active_vehicle', '{{Vite::asset("resources/img/blue_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        map.configureMarkerIcon('inactive_vehicle', '{{Vite::asset("resources/img/grey_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        map.configureMarkerIcon('selected', '{{Vite::asset("resources/img/selected_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        map.detectLocation();

        // Display vehicle marker on the map
        @if(isset($vehicle) && $vehicle->latitude && $vehicle->longitude)
            const vehicleIconTag = '{{$vehicle->status}}' === 'active' ? 'active_vehicle' : 'inactive_vehicle';
            map.addMarker('vehicle-{{$vehicle->id}}', {{$vehicle->latitude}}, {{$vehicle->longitude}}, vehicleIconTag);
            map.setView({{$vehicle->latitude}}, {{$vehicle->longitude}}, 14);
        @endif

        var page = new CreateRideRequestPage('{{env("APP_URL", "")}}', map, {{$ride->id}});
    </script>
    
@endsection

@push('scripts')
    <script>
        
    </script>
@endpush