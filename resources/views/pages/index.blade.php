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

        var id;
        var map = new RideMap('map', '{{env("NOMINATIM_URL", "")}}', '{{env("APP_URL", "")}}');
        map.setMarkerIcon('{{Vite::asset("resources/img/red_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');

        var btnDrivingMode = document.getElementById('btn-driving-mode');
        var drivingModeOption = document.getElementById('select-driving-vehicle');

        btnDrivingMode.addEventListener('click', function(){

            var drivingMode = "inactive";
            
        
            if(btnDrivingMode.getAttribute('data-state') == "off"){
                drivingMode = "active";
                btnDrivingMode.setAttribute('data-state', 'on');
                btnDrivingMode.innerHTML = "Stop driving mode";
                startLiveTracking(null);
                console.log("Tracking ID: " + id);
            }else if(btnDrivingMode.getAttribute('data-state') == "on"){
                drivingMode = "inactive";
                btnDrivingMode.setAttribute('data-state', 'off');
                btnDrivingMode.innerHTML = "Start driving mode";
                console.log("Tracking ID Stopped: " + id);
                stopLiveTracking(id);
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

                //Update vehicle location here and display it live on map.
                
            }).catch((error) => {
                throw new Error(error);
            });
        });

        function startLiveTracking(onMarkerClick){
            //Get current location
            if(navigator.geolocation){
                id = navigator.geolocation.watchPosition((position) => {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;

                    console.log("Live Marker: Latitude: " + latitude);
                    console.log("Live Marker: Longitude: " + longitude);

                    map.getMap().setView([latitude, longitude], 16);
                }, (error) => {
                    console.log("Error: " + error);
                });
            }else{
                alert("Geolocation is turned off or not supported by this device");
            }

            //var marker = L.marker([e.latlng.lat, e.latlng.lng], {icon: this.markerIcon}).addTo(this.map);
        }

        function stopLiveTracking(tag){
            navigator.geolocation.clearWatch(id);
        }
    </script>
@endpush