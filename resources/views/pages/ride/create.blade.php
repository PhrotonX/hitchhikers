@extends('layouts.app')

<x-map-head/>

@push('head')
    @vite('resources/js/ride-destination.js');
@endpush

@section('content')
    <h1>{{__('string.create_ride')}}</h1>

    <form action="/ride/create/submit" method="POST" id="rider-create-form">
        @csrf

        <x-input-label>{{__('string.ride_name')}}</x-input-label>
        <x-text-input
            :name="'ride_name'"
            :placeholder="__('string.ride_name_placeholder')"
            :value="old('ride_name')"
            :required="true"
        /><br>
        <x-input-error :messages="$errors->get('ride_name')"/>

        <x-input-label>Minimum Fare</x-input-label>
        <x-text-input
            :name="'minimum_fare'"
            :placeholder="'Enter minimum fare'"
            :value="old('minimum_fare')"
            :required="true"
        /><br>
        <x-input-error :messages="$errors->get('minimum_fare')"/>

        <x-input-label>{{__('string.fare_rate')}}</x-input-label>
        <x-text-input
            :name="'fare_rate'"
            :placeholder="__('string.fare_rate_placeholder')"
            :value="old('fare_rate')"
            :required="true"
        /><br>
        <x-input-error :messages="$errors->get('fare_rate')"/>

        <x-input-label>{{__('string.vehicle')}}</x-input-label>
        <select name="vehicle_id" id="vehicle-select" title="{{__('string.vehicle')}}">
            @foreach ($driverVehicles as $key => $value)
                <option value="{{$value->vehicle->id}}" data-lat="{{$value->vehicle->latitude}}" data-lng="{{$value->vehicle->longitude}}" data-status="{{$value->vehicle->status}}">{{$value->vehicle->vehicle_name}}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('vehicle_id')"/>

        <h2>{{__('string.destinations')}}</h2>

        <div class="destination-selector">
            <div id="map"></div>

            {{-- TODO: Make this code reusable by encapsulating it, making it OOP and move it into Map.js --}}
            <script type="module">
                import MainMap from '{{ Vite::asset("resources/js/MainMap.js") }}';

                var map = new MainMap('map', '{{env("NOMINATIM_URL", "")}}', '{{env("APP_URL", "")}}');
                map.configureMarkerIcon('default', '{{Vite::asset("resources/img/red_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
                map.configureMarkerIcon('currentPos', '{{Vite::asset("resources/img/current_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
                map.configureMarkerIcon('active_vehicle', '{{Vite::asset("resources/img/blue_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
                map.configureMarkerIcon('inactive_vehicle', '{{Vite::asset("resources/img/grey_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
                map.enableClickToAddMultipleMarkers();
                
                // Handle vehicle selection change to display vehicle marker
                const vehicleSelect = document.getElementById('vehicle-select');
                
                function updateVehicleMarker() {
                    // Clear existing vehicle marker
                    map.clearMarker('selected-vehicle');
                    
                    const selectedOption = vehicleSelect.options[vehicleSelect.selectedIndex];
                    const lat = parseFloat(selectedOption.getAttribute('data-lat'));
                    const lng = parseFloat(selectedOption.getAttribute('data-lng'));
                    const status = selectedOption.getAttribute('data-status');
                    
                    if (lat && lng) {
                        const iconTag = status === 'active' ? 'active_vehicle' : 'inactive_vehicle';
                        map.addMarker('selected-vehicle', lat, lng, iconTag);
                        map.setView(lat, lng, 14);
                    }
                }
                
                // Display initial vehicle marker
                updateVehicleMarker();
                
                // Update marker when vehicle selection changes
                vehicleSelect.addEventListener('change', updateVehicleMarker);
                map.onMapClick(function(marker, e, data){
                    // const parser = new DOMParser();
                    // const xmlDoc = parser.parseFromString(data, 'text/xml');

                    // console.log(xmlDoc);

                    // console.log(data);

                    //Add to destination list.
                    var destinationList = document.getElementById('destination-list');
                    console.log(destinationList);

                        var destinationItem = document.createElement('div');
                        destinationItem.setAttribute('class', 'destination-item');
                        
                            var destinationName = document.createElement('p');
                            destinationName.innerHTML = "<strong>"+data['display_name'];+"</strong>";
                            // console.log('Display Name: ' + data['display_name']);
                            destinationItem.appendChild(destinationName);
                            
                            var removeButton = document.createElement('button');
                            removeButton.setAttribute('type', 'button');
                            removeButton.innerHTML = "Remove";
                            removeButton.addEventListener('click', function(){
                                map.getMap().removeLayer(marker);
                                destinationList.removeChild(destinationItem);
                            });
                            var destinationCoordinates = document.createElement('p');
                            destinationCoordinates.innerHTML = 'Coordinates: ' + e.latlng.lat + ", " + e.latlng.lng;
                            destinationItem.appendChild(destinationCoordinates);

                            destinationItem.appendChild(removeButton);

                            var orderField = document.createElement('input');
                            orderField.setAttribute('type', 'number');
                            orderField.setAttribute('name', 'order[]');
                            orderField.setAttribute('value', document.getElementsByClassName('destination-item').length);
                            orderField.setAttribute('hidden', true);
                            destinationItem.appendChild(orderField);

                            var latitudeField = document.createElement('input');
                            latitudeField.setAttribute('type', 'number');
                            latitudeField.setAttribute('name', 'latitude[]');
                            latitudeField.setAttribute('value', e.latlng.lat);
                            latitudeField.setAttribute('hidden', true);
                            destinationItem.appendChild(latitudeField);

                            var longitudeField = document.createElement('input');
                            longitudeField.setAttribute('type', 'number');
                            longitudeField.setAttribute('name', 'longitude[]');
                            longitudeField.setAttribute('value', e.latlng.lng);
                            longitudeField.setAttribute('hidden', true);
                            destinationItem.appendChild(longitudeField);

                            var horizontalLine = document.createElement('hr');
                            destinationItem.appendChild(horizontalLine);

                        destinationList.appendChild(destinationItem);
                });

                // Handle map markers
                // map.on('click', function(e){
                //     console.log('Coordinates: ' + e.latlng.lat + ", " + e.latlng.lng);

                //     fetch("{{env('NOMINATIM_URL', '')}}/reverse?lat=" + e.latlng.lat + "&lon=" + e.latlng.lng + '&format=json&zoom=18&addressdetails=1')
                //         .then(response => {
                //             if(!response.ok){
                //                 throw new Error("Error: " + response);
                //             }
                //             console.log(response);

                //             return response.json();
                //         })
                //         .then(data => {
                            
                //         })
                //         .catch(error => {
                //             console.log("Error: " + error);
                //         });
                // });

                
                
            </script>
        </div>

        <div id="destination-list">
            {{-- <h3>From</h3>
            <h3>To</h3> --}}
        </div>

        @if ($errors)
            <p>{{$errors}}</p>
        @endif

        <button type="submit">
            <p>{{__('string.submit')}}</p>
        </button>
    </form>
    
@endsection

@push('scripts')
    <script>
        
    </script>
@endpush