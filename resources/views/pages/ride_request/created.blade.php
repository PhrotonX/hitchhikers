@extends('layouts.app')

<x-map-head/>

@push('head')
    @vite('resources/js/RideMap.js');
@endpush

@section('content')
    <h1>Ride Requests</h1>
    <p>Click a ride request to preview it on a map.</p>

    <div id="map"></div>

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
        map.configureMarkerIcon('currentPos', '{{Vite::asset("resources/img/current_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        map.configureMarkerIcon('active_vehicle', '{{Vite::asset("resources/img/blue_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        map.configureMarkerIcon('inactive_vehicle', '{{Vite::asset("resources/img/grey_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        map.configureMarkerIcon('selected', '{{Vite::asset("resources/img/selected_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        map.detectLocation();
    </script>

    @foreach ($rideRequests as $rideRequest)
        <div class="ride-request" id="ride-request-{{$rideRequest->id}}">
            <p><strong><span id="ride-request-destination">{{$rideRequest->ride_name}}</span></strong></p>
            {{-- @dump($rideRequest)
            @dump($rides) --}}
            <p><strong>Ride: </strong><span id="ride-request-{{$rideRequest->id}}-ride">{{$rides[$rideRequest->ride_id]->ride_name}}</span></p>
            <p><strong>Pickup Location: </strong><span id="ride-request-{{$rideRequest->id}}-time">{{$rideRequest->pickup_at}}</span></p>
            <p><strong>Vehicle Distance: </strong><span id="ride-request-{{$rideRequest->id}}-vehicle-distance">Calculating...</span></p>
            <p><strong>Time: </strong><span id="ride-request-time">{{$rideRequest->time}}</span></p>
            <p><strong>Status: </strong><span id="ride-request-status">{{$rideRequest->status}}</span></p>
            <hr>

            <script type="module">
                import getDistance from '{{ Vite::asset("resources/js/math.js") }}';                

                if(navigator.geolocation){
                    navigator.geolocation.watchPosition((position) => {
                        var latitude = position.coords.latitude;
                        var longitude = position.coords.longitude;

                        var vehicleDistanceElement = document.getElementById("ride-request-" + {{$rideRequest->id}} + "-vehicle-distance");

                        vehicleDistanceElement.innerHTML = getDistance([latitude, longitude], [{{$vehicles[$rides[$rideRequest->ride_id]->id]->latitude}}, {{$vehicles[$rides[$rideRequest->ride_id]->id]->longitude}}]);
                    }, (error) => {
                    console.log("Error: " + error);
                    });
                }
            </script>
        </div>
    @endforeach
    
    
@endsection

@push('scripts')
    <script>
        
    </script>
@endpush