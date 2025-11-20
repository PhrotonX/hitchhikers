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

        document.addEventListener('click', (e) => {
            const item = e.target.closest('.ride-request');
            if(item){
                const rideId = item.getAttribute('data-ride-id');
                // Vehicle marker
                const vlat = parseFloat(item.getAttribute('data-vehicle-lat'));
                const vlng = parseFloat(item.getAttribute('data-vehicle-lng'));
                const vstatus = item.getAttribute('data-vehicle-status');

                const toLat = parseFloat(item.getAttribute('data-to-lat'));
                const toLng = parseFloat(item.getAttribute('data-to-lng'));

                // Clear unneeded markers to make way for another ride request info displayed on map.
                map.cachedMarkers.clearLayers();
                map.vehicleMarkers.clearLayers();

                // Remove lines from the map.
                map.getMap().eachLayer((layer) => {
                    if(layer instanceof L.Polyline){
                        map.getMap().removeLayer(layer);
                    }
                });

                // Display the vehicle marker.
                if(vlat != NaN && vlng != NaN){
                    const iconTag = vstatus === 'active' ? 'active_vehicle' : 'inactive_vehicle';
                    const vehicleMarker = L.marker([vlat, vlng], {icon: map.markerIcons[iconTag]});
                    map.vehicleMarkers.addLayer(vehicleMarker);
                }
                
                // Display the selected destination location using "selected" marker.
                if(toLat != NaN && toLng != NaN){
                    map.temporaryMarker = L.marker([toLat, toLng], {
                        icon: map.markerIcons['selected']
                    });
                }
                

                //Display ride destination markers
                map.retrieveRideMarkers(rideId, true)();
            }
        });
    </script>

    @foreach ($rideRequests as $rideRequest)
        <div
            class="ride-request"
            id="ride-request-{{$rideRequest->id}}"
            data-ride-id="{{$rideRequest->ride_id}}"
            data-vehicle-lat="{{$vehicles[$rides[$rideRequest->ride_id]->id]->latitude}}"
            data-vehicle-lng="{{$vehicles[$rides[$rideRequest->ride_id]->id]->longitude}}"
            data-vehicle-status="{{$vehicles[$rides[$rideRequest->ride_id]->id]->status}}"
            data-to-lat="{{$rideRequest->to_latitude}}"
            data-to-lng="{{$rideRequest->to_longitude}}"
        >
            <p><strong><span id="ride-request-destination">{{$rideRequest->ride_name}}</span></strong></p>
            {{-- @dump($rideRequest)
            @dump($rides) --}}
            <p><strong>Ride: </strong><span id="ride-request-{{$rideRequest->id}}-ride">{{$rides[$rideRequest->ride_id]->ride_name}}</span></p>
            <p><strong>Pickup Location: </strong><span id="ride-request-{{$rideRequest->id}}-time">{{$rideRequest->pickup_at}}</span></p>
            <p><strong>Vehicle Distance: </strong><span id="ride-request-{{$rideRequest->id}}-vehicle-distance">Calculating...</span></p>
            <p><strong>Time: </strong><span id="ride-request-time">{{$rideRequest->time}}</span></p>
            <p><strong>Status: </strong><span id="ride-request-status">{{$rideRequest->status}}</span></p>
            <button type="button" id="ride-request-{{$rideRequest->id}}-cancel-btn">Delete</button>
            <button type="button" id="ride-request-{{$rideRequest->id}}-delete-btn" delete>Cancel</button>
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

                var cancelButton = document.getElementById('ride-request-' + {{$rideRequest->id}} + '-cancel-btn');
                var deleteButton = document.getElementById('ride-request-' + {{$rideRequest->id}} + '-delete-btn');

                if("{{$rideRequest->status}}" != "cancelled"){
                    cancelButton.hidden = true;
                    deleteButton.hidden = false;
                }else{
                    cancelButton.hidden = false;
                    deleteButton.hidden = true;
                }

                cancelButton.addEventListener('click', () => {
                    
                });
            </script>
        </div>
    @endforeach
    
    
@endsection

@push('scripts')
    <script>
        
    </script>
@endpush