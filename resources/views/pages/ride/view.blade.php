@extends('layouts.app')

<x-map-head/>

@section('content')
    <div class="ride-view-container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <button onclick="history.back()">{{ __('string.back') }}</button>
        
        <h1>{{ $ride->ride_name }}</h1>

        <div class="ride-details">
            <h2>{{ __('string.ride_details') }}</h2>
            <p><strong>{{ __('string.status') }}:</strong> {{ $ride->status ?: __('string.not_set') }}</p>
            <p><strong>{{ __('string.fare_rate') }}:</strong> {{ $ride->fare_rate }}</p>
            @if(isset($ride->minimum_fare))
                <p><strong>{{ __('string.minimum_fare') }}:</strong> {{ $ride->minimum_fare }}</p>
            @endif
            <p><strong>{{ __('string.rating') }}:</strong> {{ $ride->rating ?? __('string.no_rating') }}</p>
            <p><strong>{{ __('string.created_at') }}:</strong> {{ $ride->created_at->format('M d, Y h:i A') }}</p>
            <p><strong>{{ __('string.updated_at') }}:</strong> {{ $ride->updated_at->format('M d, Y h:i A') }}</p>
        </div>

        <hr>

        <div class="driver-details">
            <h2>{{ __('string.driver') }}</h2>
            @if($ride->driver)
                <p><strong>{{ __('string.driver_name') }}:</strong> {{ $ride->driver->driver_account_name }}</p>
                <p><strong>{{ __('string.company') }}:</strong> {{ $ride->driver->company ?: __('string.independent') }}</p>
            @else
                <p>{{ __('string.driver_not_found') }}</p>
            @endif
        </div>

        <hr>

        <div class="destinations-section">
            <h2>{{ __('string.destinations') }}</h2>
            
            @if($destinations && $destinations->count() > 0)
                <div id="map" style="height: 400px; width: 100%; margin-bottom: 20px;"></div>
                
                <div class="destinations-list">
                    @foreach($destinations as $index => $destination)
                        <div class="destination-item">
                            <p><strong>{{ $index == 0 ? __('string.from') : ($index == $destinations->count() - 1 ? __('string.to') : __('string.stop') . ' ' . $index) }}:</strong></p>
                            <p>{{ __('string.coordinates') }}: {{ $destination->latitude }}, {{ $destination->longitude }}</p>
                        </div>
                    @endforeach
                </div>

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
                    
                    // Load ride markers with line and auto-fit bounds
                    map.retrieveRideMarkers({{ $ride->id }}, true, true)();
                </script>
            @else
                <p>{{ __('string.no_destinations') }}</p>
            @endif
        </div>

        <hr>

        <div class="ride-actions">
            @can('update', $ride)
                <a href="{{ route('ride.edit', $ride->id) }}" class="btn btn-primary">{{ __('string.edit_ride') }}</a>
            @endcan
            
            @can('delete', $ride)
                <a href="{{ route('ride.delete', $ride->id) }}" class="btn btn-danger">{{ __('string.delete_ride') }}</a>
            @endcan
        </div>
    </div>
@endsection