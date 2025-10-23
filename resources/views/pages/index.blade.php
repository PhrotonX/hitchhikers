@extends('layouts.app')
<x-map-head/>

@push('head')
    <meta name="csrf-token" content={{csrf_token()}}
@endpush

@section('content')
    <div id="map"></div>

    @auth
        {{-- Show driving mode form if the user account has a driver account --}}
        @if (Auth::user()->isDriver())
            <a href="/ride/create">Create a ride</a>
            <div id="driving-mode">
                <button type="button" id="btn-driving-mode" data-state="off">Start driving mode</button>
                {{-- @TODO: Insert a dropdown menu here to be able to choose a ride to begin with. --}}
                {{-- Use JavaScript to perform the driving mode. --}}

                <select name="driving-mode-option" id="select-driving-vehicle">
                    @foreach (Auth::user()->getRides() as $ride)
                        <option value="{{$ride->id}}">{{$ride->ride_name}}</option>
                    @endforeach
                </select>
            </div>
        @endif
    @endauth
    
    
    
@endsection

@push('scripts')
    <script type="module">
        import RideMap from '{{ Vite::asset("resources/js/RideMap.js") }}';

        var trackingId = null;
        var vehicleId = null;
        var vehicleMarker = null;

        var map = new RideMap('map', '{{env("NOMINATIM_URL", "")}}', '{{env("APP_URL", "")}}');
        map.setMarkerIcon('{{Vite::asset("resources/img/red_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        
        navigator.geolocation.getCurrentPosition((pos) => {
            vehicleMarker = L.marker([pos.coords.latitude, pos.coords.longitude], {icon: map.markerIcon}).addTo(map.getMap());
        })

        var btnDrivingMode = document.getElementById('btn-driving-mode');
        var drivingModeOption = document.getElementById('select-driving-vehicle');

        btnDrivingMode.addEventListener('click', function(){

            var drivingMode = "inactive";
            
        
            if(btnDrivingMode.getAttribute('data-state') == "off"){
                drivingMode = "active";
                btnDrivingMode.setAttribute('data-state', 'on');
                btnDrivingMode.innerHTML = "Stop driving mode";
                
            }else if(btnDrivingMode.getAttribute('data-state') == "on"){
                drivingMode = "inactive";
                btnDrivingMode.setAttribute('data-state', 'off');
                btnDrivingMode.innerHTML = "Start driving mode";
            }

            fetch('{{env("APP_URL", "")}}' + '/ride/'+drivingModeOption.value+'/update-status', {
                method: "PATCH",
                body: JSON.stringify({
                    status: drivingMode,
                }),
                headers: {
                    "Content-type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-Token": document.querySelector('meta[name=csrf-token]').content,
                },
            })
            .then((response) => {
                return response.json();
            }).then((data) => {
                console.log(data);

                vehicleId = data.ride.vehicle_id;

                console.log("Vehicle ID: " + vehicleId);

                if(drivingMode == "active"){
                    startLiveTracking(null, vehicleId);
                    console.log("Tracking ID: " + trackingId);
                }else{
                    stopLiveTracking(trackingId);
                    console.log("Tracking ID Stopped: " + trackingId);
                }

                //@TODO: Update vehicle location here and display it live on map.
            }).catch((error) => {
                throw new Error(error);
            });
        });

        function startLiveTracking(onMarkerClick, vehicle_id){
            //Get current location
            if(navigator.geolocation){
                trackingId = navigator.geolocation.watchPosition((position) => {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;

                    console.log("Live Marker: Latitude: " + latitude);
                    console.log("Live Marker: Longitude: " + longitude);

                    //@TODO: Change the map marker color from gray to blue.

                    //Position the map where the current location is pointing to.
                    map.getMap().setView([latitude, longitude], 16);

                    //Update the position of the marker indicating the vehicle's position.
                    vehicleMarker.setLatLng([latitude, longitude]);

                    //Save the position data into the database.
                    //=========================================
                    fetch('{{env("APP_URL", "")}}' + '/vehicle/'+vehicle_id+'/update-location', {
                        method: "PATCH",
                        body: JSON.stringify({
                            latitude: latitude,
                            longitude: longitude,
                        }),
                        headers: {
                            "Content-type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-Token": document.querySelector('meta[name=csrf-token]').content,
                        },
                    }).then((response) => {
                        return response.json();
                    }).then((data) => {
                        console.log(data);
                    }).catch((error) => {
                        throw new Error(error);
                    });
                    //=========================================

                }, (error) => {
                console.log("Error: " + error);
            });
            }else{
                alert("Geolocation is turned off or not supported by this device");
            }
        }

        function stopLiveTracking(tag){
            navigator.geolocation.clearWatch(trackingId);

            //@TODO: Change the marker color from blue to gray.
        }
    </script>
@endpush