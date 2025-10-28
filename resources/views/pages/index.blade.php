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
                <button type="button" id="btn-driving-mode">Start driving mode</button>
                {{-- @TODO: Insert a dropdown menu here to be able to choose a ride to begin with. --}}
                {{-- Use JavaScript to perform the driving mode. --}}

                <select name="driving-mode-option" id="select-driving-vehicle">
                    @foreach (Auth::user()->getRides() as $ride)
                        <option id="ride-option-{{$ride->id}}" value="{{$ride->id}}" data-status="{{$ride->status}}">{{$ride->ride_name}}</option>
                    @endforeach
                </select>
            </div>
        @endif
    @endauth
    
    <div id="infobox"></div>
    
@endsection

@push('scripts')
    <script type="module">
        import RideMap from '{{ Vite::asset("resources/js/RideMap.js") }}';

        var infobox = document.getElementById('infobox');
        
        var btnDrivingMode = document.getElementById('btn-driving-mode');
        var drivingModeOption = document.getElementById('select-driving-vehicle');
        var selectedDrivingModeOption;
        var status;

        var map = new RideMap('map', '{{env("NOMINATIM_URL", "")}}', '{{env("APP_URL", "")}}');
        map.configureMarkerIcon('default', '{{Vite::asset("resources/img/red_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        map.configureMarkerIcon('currentPos', '{{Vite::asset("resources/img/current_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        map.configureMarkerIcon('active_vehicle', '{{Vite::asset("resources/img/blue_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        map.configureMarkerIcon('inactive_vehicle', '{{Vite::asset("resources/img/grey_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        map.detectLocation();
        // Functionality for viewing vehicle information
        map.setOnVehicleMarkerClick((e, data) => {

            infobox.innerHTML =
                '<div id="ride-popup"><button type="button" id="ride-popup-close-btn">Close</button><br>' + 
                "<p><strong>"+data.vehicle_name+"</strong></p>" + 
                '<p id="ride-location">Retrieving location...</p>' +
                "<p><strong>Status:</strong>" + data.status + "</p>" +
                "<p>"+data.latitude+", "+data.longitude+"</p>" + 
                '<button type="button" id="ride-view-review-btn">View Reviews</button></div>';
            infobox.style.display = "block";
            
            // Set up ride-popup-close-btn
            infobox.addEventListener('click', (e) => {
                if (e.target && e.target.id === 'ride-popup-close-btn') {
                    infobox.innerHTML = "";
                    infobox.style.display = "none";
                    map.cachedMarkers.clearLayers();
                }
            });

            map.reverseGeocode(data.latitude, data.longitude).then((location) => {
                document.getElementById('ride-location').innerHTML = location.display_name;
            });
            
            @if (Auth::user() == null || !(Auth::user()->isDriver()))
                infobox.innerHTML += '<strong>Available rides: </strong><select id="ride-list" name="ride-list"></select>' +
                '<button type="button">See More</button>';
            
                var rideList = document.getElementById('ride-list');
                
                map.retrieveRides(data.id)().then((data) => {
                    var count = Object.keys(data.rides).length;

                    rideList.innerHTML = "";

                    for(let i = -1; i < count; i++){
                        var option = document.createElement("option");
                        if(i == -1){
                            option.setAttribute("disabled", true);
                            option.setAttribute("selected", true);
                            option.innerHTML = "---";
                        }else{
                            option.setAttribute("id", "ride-option-"+data.rides[i].id);
                            option.setAttribute("value", data.rides[i].id);
                            option.innerHTML = data.rides[i].ride_name;
                        }
                        

                        rideList.appendChild(option);
                    }

                    rideList.addEventListener('change', () => {
                        getRides(rideList.value);
                    });
                });

                // getRides(rideList.value);
            @endif
        });
        // map.enablePanToRetrieveAllRideMarkers();
        map.enablePanToRetrieveVehicleMarkers();
        
        @auth
            @if (Auth::user()->isDriver())

                updateSelectedRideOption();

                drivingModeOption.addEventListener('change', function(){
                    updateSelectedRideOption();
                });

                btnDrivingMode.addEventListener('click', function(){
                    var drivingMode = "inactive";                    
                    selectedDrivingModeOption = document.getElementById("ride-option" + "-" + drivingModeOption.value);

                    if(status == "active"){
                        drivingMode = "inactive";
                        selectedDrivingModeOption.setAttribute('data-status', 'inactive');
                    }else{
                        drivingMode = "active";
                        selectedDrivingModeOption.setAttribute('data-status', 'active');
                    }

                    updateSelectedRideOption();

                    console.log("Mode: "+ drivingMode);
                    
                    // Update both ride and vehicle driving mode status and marker icon color.
                    // ========================================================================

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
                            // console.log(vehicleData);

                            // console.log("Vehicle ID: " + vehicleData.vehicle.id);

                            if(drivingMode == "active"){
                                map.startLiveTracking(null, vehicleData.vehicle.id);
                                console.log("Tracking ID: " + map.trackingId);
                            }else{
                                map.stopLiveTracking(map.trackingId);
                                console.log("Tracking ID Stopped: " + map.trackingId);
                            }

                            //CHange vehicle icon color
                            map.setMarkerIcon("vehicle-" + vehicleData.vehicle.id, status + "_vehicle");

                            //@TODO: Update vehicle location here and display it live on map.
                        }).catch((error) => {
                            throw new Error(error);
                        });
                    }).catch((error) => {
                        throw new Error(error);
                    });

                    
                });
            @endif

            // Handle driving mode select list.
            function updateSelectedRideOption(){
                //Retrieves data needed to be processed.
                var selectedDrivingModeOption = document.getElementById("ride-option" + "-" + drivingModeOption.value);
                status = selectedDrivingModeOption.getAttribute('data-status');

                // Toggles button state.
                if(status == "active"){
                    btnDrivingMode.innerHTML = "Stop driving mode";
                }else{
                    btnDrivingMode.innerHTML = "Start driving mode";
                }
                
                getRides(drivingModeOption.value);

                // Zoom into the position of associated vehicle from a selected ride.
                fetch('{{env("APP_URL", "")}}' + '/ride/'+drivingModeOption.value)
                .then((response) => {
                    return response.json();
                }).then((data) => {

                    fetch('{{env("APP_URL", "")}}' + '/api/vehicle/'+data.ride.vehicle_id)
                    .then((response) => {
                        console.log("URL: " + '{{env("APP_URL", "")}}' + '/vehicle/'+data.ride.vehicle_id);
                        return response.json();
                    }).then((vehicleData) => {
                        map.getMap().setView([vehicleData.vehicle.latitude, vehicleData.vehicle.longitude], 16);
                    }).catch((error) => {
                        throw new Error(error);
                    });

                }).catch((error) => {
                    throw new Error(error);
                });
            }

        @endauth

        
        // Retrieves ride destinations based on ride ID.
        function getRides(rideId){
            map.retrieveRideMarkers(rideId)();
        }
        
    </script>
@endpush