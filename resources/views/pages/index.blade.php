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
        map.setMarkerIcon('{{Vite::asset("resources/img/red_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        // map.onMapClick(function(marker, e, data){
        //     // const parser = new DOMParser();
        //     // const xmlDoc = parser.parseFromString(data, 'text/xml');
        
        //     // console.log(xmlDoc);

        //     // console.log(data);
        // })

        
        var btnDrivingMode = document.getElementById('btn-driving-mode');
        var drivingModeOption = document.getElementById('select-driving-vehicle');

        btnDrivingMode.addEventListener('click', function(){

            var drivingMode = "inactive";
        
            if(btnDrivingMode.getAttribute('data-state') == "off"){
                drivingMode = "active";
                btnDrivingMode.setAttribute('data-state', 'on');
            }else if(btnDrivingMode.getAttribute('data-state') == "on"){
                drivingMode = "inactive";
                btnDrivingMode.setAttribute('data-state', 'off');
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
            }).catch((error) => {
                throw new Error(error);
            });
        });

        function toggleDrivingMode(){
            
        }
    </script>
@endpush