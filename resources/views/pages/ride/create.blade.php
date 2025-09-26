@extends('layouts.app')

<x-map-head/>

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

        <x-input-label>{{__('string.fare_rate')}}</x-input-label>
        <x-text-input
            :name="'fare_rate'"
            :placeholder="__('string.fare_rate_placeholder')"
            :value="old('fare_rate')"
            :required="true"
        /><br>
        <x-input-error :messages="$errors->get('fare_rate')"/>

        <x-input-label>{{__('string.vehicle')}}</x-input-label>
        <select name="vehicle_id" title="{{__('string.vehicle')}}">
            @foreach ($driverVehicles as $key => $value)
                <option value="{{$value->vehicle->id}}">{{$value->vehicle->vehicle_name}}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('vehicle_id')"/>

        <h2>{{__('string.destinations')}}</h2>

        <div class="destination-selector">
            <div id="map"></div>

            {{-- TODO: Make this code reusable by encapsulating it, making it OOP and move it into Map.js --}}
            <script>
                var map = L.map('map', {doubleClickZoom: false}).locate({setView: true, maxZoom: 16});

                //Define the marker icon.
                var markerIcon = L.icon({
                    iconUrl: '{{Vite::asset("resources/img/red_pin.png")}}',
                    shadowUrl: '{{Vite::asset("resources/img/shadow_pin.png")}}',
                    
                    iconSize:     [38, 95],
                    shadowSize:   [50, 64],
                    iconAnchor:   [22, 94],
                    shadowAnchor: [4, 62], 
                    popupAnchor:  [-3, -76]
                });

                // Handle map markers
                map.on('click', function(e){
                    console.log('Coordinates: ' + e.latlng.lat + ", " + e.latlng.lng);

                    fetch("{{env('NOMINATIM_URL', '')}}/reverse?lat=" + e.latlng.lat + "&lon=" + e.latlng.lng + '&format=json&zoom=18&addressdetails=1')
                        .then(response => {
                            if(!response.ok){
                                throw new Error("Error: " + response);
                            }
                            console.log(response);

                            return response.json();
                        })
                        .then(data => {
                            // const parser = new DOMParser();
                            // const xmlDoc = parser.parseFromString(data, 'text/xml');

                            // console.log(xmlDoc);

                            console.log(data);

                            //Add markers
                            var marker = L.marker([e.latlng.lat, e.latlng.lng], {icon: markerIcon}).addTo(map);

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
                                        map.removeLayer(marker);
                                        destinationList.removeChild(destinationItem);
                                    });

                                    destinationItem.appendChild(removeButton);

                                    var destinationCoordinates = document.createElement('p');
                                    destinationCoordinates.innerHTML = 'Coordinates: ' + e.latlng.lat + ", " + e.latlng.lng;
                                    destinationItem.appendChild(destinationCoordinates);

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

                                destinationList.appendChild(destinationItem);
                        })
                        .catch(error => {
                            console.log("Error: " + error);
                        });
                });

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);
                
            </script>
        </div>

        <div id="destination-list">
            <h3>{{__('string.from')}}</h3>
            <h3>{{__('string.to')}}</h3>
            
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