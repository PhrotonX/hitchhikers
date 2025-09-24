@extends('layouts.app')

<x-map-head/>

@section('content')
    <h1>{{__('string.create_ride')}}</h1>

    <form action="/ride/create/submit" method="POST">
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

                // Insert Leaftlet Control Geocoder Plugin.
                // new L.Control.Geocoder().addTo(map);

                // Handle map markers
                map.on('click', function(e){
                    console.log('Coordinates: ' + e.latlng.lat + ", " + e.latlng.lng);
                    // var results = L.Control.geocoders.geocoder.reverse(e.latlng, map.getZoom());
                    // console.log('Results: ' + results);

                    //Add markers
                    L.marker([e.latlng.lat, e.latlng.lng], {icon: markerIcon}).addTo(map);

                    //Add to destination list.
                    var destinationList = document.getElemenById('destination-list');

                        var destinationItem = document.createElement('div');
                        
                            var destinationName = document.createElement('p');
                            // @TODO: Obtain destination name here using Leaflet.
                            // destinationName.innerHTML = 

                            var destinationCoordinates = document.createElement('p');
                            destinationCoordinates.innerHTML = 'Coordinates: ' + e.latlng.lat + ", " + e.latlng.lng;
                            destinationItem.appendChild(destinationItem);
                });

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                // Configure geocoder
                // var geocoder = L.Control.geocoder({
                //     defaultMarkGeocode: false
                // }).on('markgeocode', function(e){
                    
                // })
                
            </script>
        </div>

        <div class="destination-list">
            <h3>{{__('string.from')}}</h3>
            <h3>{{__('string.to')}}</h3>
            
        </div>
    </form>
    
    
@endsection

@push('scripts')
    <script>
        
    </script>
@endpush