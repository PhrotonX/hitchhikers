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
                if(vlat && vlng){
                    const iconTag = vstatus === 'active' ? 'active_vehicle' : 'inactive_vehicle';
                    const vehicleMarker = L.marker([vlat, vlng], {icon: map.markerIcons[iconTag]});
                    map.vehicleMarkers.addLayer(vehicleMarker);
                }
                
                // Display the selected destination location using "selected" marker.
                if(toLat && toLng){
                    map.temporaryMarker = L.marker([toLat, toLng], {
                        icon: map.markerIcons['selected']
                    });
                }
                

                //Display ride destination markers
                map.retrieveRideMarkers(rideId, true)();
            }
        });
    </script>

    @isset($rideRequests)
        @foreach ($rideRequests as $rideRequest)
            @php
                $ride = $rides[$rideRequest->ride_id] ?? null;
                $vehicle = $ride ? ($vehicles[$ride->vehicle_id] ?? null) : null;
            @endphp
            @if($ride && $vehicle)
            <div
                class="ride-request"
                id="ride-request-{{$rideRequest->id}}"
                data-ride-id="{{$rideRequest->ride_id}}"
                data-vehicle-lat="{{$vehicle->latitude}}"
                data-vehicle-lng="{{$vehicle->longitude}}"
                data-vehicle-status="{{$vehicle->status}}"
                data-to-lat="{{$rideRequest->to_latitude}}"
                data-to-lng="{{$rideRequest->to_longitude}}"
            >
                <p><strong><span id="ride-request-{{$rideRequest->id}}-destination">{{$rideRequest->ride_name}}</span></strong></p>
                {{-- @dump($rideRequest)
                @dump($rides) --}}
                <p><strong>Ride: </strong><span id="ride-request-{{$rideRequest->id}}-ride">{{$ride->ride_name}}</span></p>
                <p><strong>Pickup Location: </strong><span id="ride-request-{{$rideRequest->id}}-time">{{$rideRequest->pickup_at}}</span></p>
                <p><strong>Vehicle Distance: </strong><span id="ride-request-{{$rideRequest->id}}-vehicle-distance">Calculating...</span></p>
                <p><strong>Time: </strong><span id="ride-request-{{$rideRequest->id}}-time">{{$rideRequest->time}}</span></p>
                <p><strong>Status: </strong><span id="ride-request-{{$rideRequest->id}}-status">{{__("ride_request_status.$rideRequest->status")}}</span></p>
                <button type="button" class="ride-request-cancel-btn" id="ride-request-{{$rideRequest->id}}-cancel-btn">Cancel</button>
                <button type="button" class="ride-request-delete-btn" id="ride-request-{{$rideRequest->id}}-delete-btn">Delete</button>
                <hr>

                <script type="module">
                    import getDistance from '{{ Vite::asset("resources/js/math.js") }}';                

                    @if($vehicle && $vehicle->latitude && $vehicle->longitude)
                    if(navigator.geolocation){
                        navigator.geolocation.watchPosition((position) => {
                            var latitude = position.coords.latitude;
                            var longitude = position.coords.longitude;

                            var vehicleDistanceElement = document.getElementById("ride-request-" + {{$rideRequest->id}} + "-vehicle-distance");

                            vehicleDistanceElement.innerHTML = getDistance([latitude, longitude], [{{$vehicle->latitude}}, {{$vehicle->longitude}}]);
                        }, (error) => {
                        console.log("Error: " + error);
                        });
                    }
                    @else
                    document.getElementById("ride-request-" + {{$rideRequest->id}} + "-vehicle-distance").innerHTML = "N/A";
                    @endif

                    var itemDiv = document.getElementById('ride-request-' + {{$rideRequest->id}});
                    var cancelButton = document.getElementById('ride-request-' + {{$rideRequest->id}} + '-cancel-btn');
                    var deleteButton = document.getElementById('ride-request-' + {{$rideRequest->id}} + '-delete-btn');
                    var statusSpan = document.getElementById('ride-request-' + {{$rideRequest->id}} + '-status');

                    // Apply initial styling based on status
                    applyStyling();
                    toggleButtons();
                    
                    cancelButton.addEventListener('click', cancelItem);

                    deleteButton.addEventListener('click', deleteItem);

                    function applyStyling() {
                        const status = "{{$rideRequest->status}}";
                        
                        if (status === 'approved') {
                            statusSpan.innerHTML = "<span style='color: green;'>✓ Approved</span>";
                            itemDiv.style.backgroundColor = '#d4edda';
                            itemDiv.style.border = '2px solid #28a745';
                            itemDiv.style.padding = '10px';
                            itemDiv.style.marginBottom = '10px';
                            itemDiv.style.borderRadius = '5px';
                            cancelButton.style.display = 'none';
                            deleteButton.style.display = 'none';
                        } else if (status === 'rejected') {
                            statusSpan.innerHTML = "<span style='color: red;'>✗ Rejected</span>";
                            itemDiv.style.backgroundColor = '#f8d7da';
                            itemDiv.style.border = '2px solid #dc3545';
                            itemDiv.style.padding = '10px';
                            itemDiv.style.marginBottom = '10px';
                            itemDiv.style.borderRadius = '5px';
                            cancelButton.style.display = 'none';
                            deleteButton.style.display = 'inline-block';
                        } else if (status === 'cancelled') {
                            statusSpan.innerHTML = "<span style='color: gray;'>✗ Cancelled</span>";
                            itemDiv.style.backgroundColor = '#f8f9fa';
                            itemDiv.style.border = '2px solid #6c757d';
                            itemDiv.style.padding = '10px';
                            itemDiv.style.marginBottom = '10px';
                            itemDiv.style.borderRadius = '5px';
                        } else if (status === 'pending') {
                            statusSpan.innerHTML = "<span style='color: orange;'>⏳ Pending</span>";
                            itemDiv.style.backgroundColor = '#fff3cd';
                            itemDiv.style.border = '2px solid #ffc107';
                            itemDiv.style.padding = '10px';
                            itemDiv.style.marginBottom = '10px';
                            itemDiv.style.borderRadius = '5px';
                        } else {
                            itemDiv.style.padding = '10px';
                            itemDiv.style.marginBottom = '10px';
                            itemDiv.style.border = '1px solid #ddd';
                            itemDiv.style.borderRadius = '5px';
                        }
                    }

                    function cancelItem(){
                        if (!confirm('Are you sure you want to cancel this ride request?')) {
                            return;
                        }
                        
                        cancelButton.disabled = true;
                        
                        fetch('{{env("APP_URL", "")}}/ride/requests/'+{{$rideRequest->id}}+'/update-status', {
                            method: 'PATCH',
                            body: JSON.stringify({
                                status: 'cancelled'
                            }),
                            headers: {
                                "Content-type": "application/json",
                                "Accept": "application/json",
                                "X-CSRF-Token": document.querySelector('meta[name=csrf-token]').content,
                            },
                        }).then((response) => {
                            return response.json();
                        }).then((data) => {
                            statusSpan.innerHTML = "<span style='color: gray;'>✗ Cancelled</span>";
                            itemDiv.style.backgroundColor = '#f8f9fa';
                            itemDiv.style.border = '2px solid #6c757d';
                            cancelButton.hidden = true;
                            deleteButton.hidden = false;
                            alert('Ride request cancelled successfully.');
                        }).catch((error) => {
                            console.error(error);
                            alert('Failed to cancel request. Please try again.');
                            cancelButton.disabled = false;
                        });
                    }

                    function deleteItem(){
                        if (!confirm('Are you sure you want to delete this ride request? This action cannot be undone.')) {
                            return;
                        }
                        
                        deleteButton.disabled = true;
                        
                        fetch('{{env("APP_URL", "")}}' + '/ride/requests/'+{{$rideRequest->id}}+'/delete', {
                            method: 'DELETE',
                            headers: {
                                "Content-type": "application/json",
                                "Accept": "application/json",
                                "X-CSRF-Token": document.querySelector('meta[name=csrf-token]').content,
                            },
                        }).then((response) => {
                            return response.json();
                        }).then((data) => {
                            cancelButton.removeEventListener('click', cancelItem);
                            deleteButton.removeEventListener('click', deleteItem);
                            itemDiv.style.transition = 'opacity 0.3s';
                            itemDiv.style.opacity = '0';
                            setTimeout(() => {
                                itemDiv.remove();
                            }, 300);
                        }).catch((error) => {
                            console.error(error);
                            alert('Failed to delete request. Please try again.');
                            deleteButton.disabled = false;
                        });
                    }

                    function toggleButtons(){
                        if("{{$rideRequest->status}}" == "cancelled"){
                            cancelButton.hidden = true;
                            deleteButton.hidden = false;
                        }else{
                            cancelButton.hidden = false;
                            deleteButton.hidden = true;
                        }

                    }
                </script>
            </div>
            @endif
        @endforeach
    @else
        <p>Empty!</p>
    @endisset
    
    
    
@endsection

@push('scripts')
    <script>
        
    </script>
@endpush