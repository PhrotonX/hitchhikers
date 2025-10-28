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

        var map = new RideMap('map', '{{env("NOMINATIM_URL", "")}}', '{{env("APP_URL", "")}}');
        map.setMarkerIcon('default', '{{Vite::asset("resources/img/red_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        map.setMarkerIcon('currentPos', '{{Vite::asset("resources/img/current_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        map.setMarkerIcon('active_vehicle', '{{Vite::asset("resources/img/blue_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        map.setMarkerIcon('inactive_vehicle', '{{Vite::asset("resources/img/grey_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        map.detectLocation();
        map.setOnVehicleMarkerClick((e, data) => {
            console.log("Marker clicked! e: " + e);
            console.log("Marker clicked! data: " + data);
        });
        map.enablePanToRetrieveRideMarkers();
        map.enablePanToRetrieveVehicleMarkers();
        
        
        var btnDrivingMode = document.getElementById('btn-driving-mode');
        var drivingModeOption = document.getElementById('select-driving-vehicle');

        @auth
            @if (Auth::user()->isDriver())
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

                    console.log("Mode: "+ drivingMode);
                    
                    // Update both ride and vehicle status
                    // ================================================
                    // Update ride status
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

                        // Update vehicle status
                        fetch('{{env("APP_URL", "")}}' + '/vehicle/'+data.ride.vehicle_id+'/update-status', {
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
                        }).then((vehicleData) => {
                            console.log(vehicleData);

                            console.log("Vehicle ID: " + vehicleData.vehicle.id);

                            if(drivingMode == "active"){
                                map.startLiveTracking(null, vehicleData.vehicle.id);
                                console.log("Tracking ID: " + map.trackingId);
                            }else{
                                map.stopLiveTracking(map.trackingId);
                                console.log("Tracking ID Stopped: " + map.trackingId);
                            }

                            //@TODO: Update vehicle location here and display it live on map.
                        }).catch((error) => {
                            throw new Error(error);
                        });
                    }).catch((error) => {
                        throw new Error(error);
                    });

                    
                });
            @endif
        @endauth
        

        
    </script>
@endpush