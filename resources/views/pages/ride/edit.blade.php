@extends('layouts.app')

<x-map-head/>

@push('head')
    @vite(['resources/js/ride-destination.js', 'resources/css/driver-dashboard.css']);
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
<div class="main-layout">
    <x-sidebar-nav />

    <main class="main-content">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">{{__('string.edit_ride')}}</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('ride.update', $ride->id) }}" method="POST" id="rider-edit-form">
        @csrf
        @method('PATCH')

        <x-input-label>{{__('string.ride_name')}}</x-input-label>
        <x-text-input
            :name="'ride_name'"
            :placeholder="__('string.ride_name_placeholder')"
            :value="old('ride_name', $ride->ride_name)"
            :required="true"
        /><br>
        <x-input-error :messages="$errors->get('ride_name')"/>

        <x-input-label>Minimum Fare</x-input-label>
        <x-text-input
            :name="'minimum_fare'"
            :placeholder="'Enter minimum fare'"
            :value="old('minimum_fare', $ride->minimum_fare ?? '')"
            :required="true"
        /><br>
        <x-input-error :messages="$errors->get('minimum_fare')"/>

        <x-input-label>{{__('string.fare_rate')}}</x-input-label>
        <x-text-input
            :name="'fare_rate'"
            :placeholder="__('string.fare_rate_placeholder')"
            :value="old('fare_rate', $ride->fare_rate)"
            :required="true"
        /><br>
        <x-input-error :messages="$errors->get('fare_rate')"/>

        <x-input-label>{{__('string.vehicle')}}</x-input-label>
        <select name="vehicle_id" title="{{__('string.vehicle')}}">
            @foreach ($driverVehicles as $key => $value)
                <option value="{{$value->vehicle->id}}" {{ $ride->vehicle_id == $value->vehicle->id ? 'selected' : '' }}>
                    {{$value->vehicle->vehicle_name}}
                </option>
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
                map.enableClickToAddMultipleMarkers();

                // Load existing destinations
                var existingDestinations = [];
                @foreach ($destinations as $destination)
                    existingDestinations.push({
                        lat: {{ $destination->latitude }},
                        lng: {{ $destination->longitude }},
                        order: {{ $destination->order }}
                    });
                @endforeach

                // Counter for marker tags
                var markerCounter = 0;

                map.onMapClick(function(marker, e, data){
                    //Add to destination list.
                    var destinationList = document.getElementById('destination-list');

                    var destinationItem = document.createElement('div');
                    destinationItem.setAttribute('class', 'destination-item');
                    destinationItem.setAttribute('draggable', 'true');
                    
                    var destinationName = document.createElement('p');
                    destinationName.innerHTML = "<strong>"+data['display_name']+"</strong>";
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

                // Load existing destinations after map is ready
                window.addEventListener('load', function() {
                    existingDestinations.forEach(function(dest, index) {
                        var markerTag = 'existingMarker' + index;
                        map.addMarker(markerTag, dest.lat, dest.lng, 'default');
                        var marker = map.markers[markerTag];
                        
                        fetch("{{env('NOMINATIM_URL', '')}}/reverse?lat=" + dest.lat + "&lon=" + dest.lng + '&format=json&zoom=18&addressdetails=1')
                            .then(response => response.json())
                            .then(data => {
                                var destinationList = document.getElementById('destination-list');
                                
                                var destinationItem = document.createElement('div');
                                destinationItem.setAttribute('class', 'destination-item');
                                destinationItem.setAttribute('draggable', 'true');
                                
                                var destinationName = document.createElement('p');
                                destinationName.innerHTML = "<strong>"+data['display_name']+"</strong>";
                                destinationItem.appendChild(destinationName);
                                
                                var removeButton = document.createElement('button');
                                removeButton.setAttribute('type', 'button');
                                removeButton.innerHTML = "Remove";
                                removeButton.addEventListener('click', function(){
                                    map.getMap().removeLayer(marker);
                                    destinationList.removeChild(destinationItem);
                                });
                                
                                var destinationCoordinates = document.createElement('p');
                                destinationCoordinates.innerHTML = 'Coordinates: ' + dest.lat + ", " + dest.lng;
                                destinationItem.appendChild(destinationCoordinates);

                                destinationItem.appendChild(removeButton);

                                var orderField = document.createElement('input');
                                orderField.setAttribute('type', 'number');
                                orderField.setAttribute('name', 'order[]');
                                orderField.setAttribute('value', index);
                                orderField.setAttribute('hidden', true);
                                destinationItem.appendChild(orderField);

                                var latitudeField = document.createElement('input');
                                latitudeField.setAttribute('type', 'number');
                                latitudeField.setAttribute('name', 'latitude[]');
                                latitudeField.setAttribute('value', dest.lat);
                                latitudeField.setAttribute('hidden', true);
                                destinationItem.appendChild(latitudeField);

                                var longitudeField = document.createElement('input');
                                longitudeField.setAttribute('type', 'number');
                                longitudeField.setAttribute('name', 'longitude[]');
                                longitudeField.setAttribute('value', dest.lng);
                                longitudeField.setAttribute('hidden', true);
                                destinationItem.appendChild(longitudeField);

                                var rideAddressField = document.createElement('input');
                                rideAddressField.setAttribute('type', 'text');
                                rideAddressField.setAttribute('name', 'ride_address[]');
                                rideAddressField.setAttribute('value', data['display_name']);
                                rideAddressField.setAttribute('hidden', true);
                                destinationItem.appendChild(rideAddressField);

                                var horizontalLine = document.createElement('hr');
                                destinationItem.appendChild(horizontalLine);

                                destinationList.appendChild(destinationItem);
                            })
                            .catch(error => {
                                console.log("Error fetching location: " + error);
                            });
                    });
                });
            </script>
        </div>

        <div id="destination-list">
            
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="margin-top: 20px; padding: 15px; background: var(--error); color: white; border-radius: 8px;">
                @foreach ($errors->all() as $error)
                    <p style="margin: 5px 0;">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <button type="submit" class="btn btn-primary" style="margin-top: 20px;">
            <i class="fa-solid fa-check"></i> {{__('string.update')}}
        </button>
    </form>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
    <script>
        
    </script>
@endpush