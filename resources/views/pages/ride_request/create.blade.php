@extends('layouts.app')

<x-map-head/>

@push('head')
    @vite('resources/js/RideMap.js');
@endpush

@section('content')
    <h1>Make a Ride Request</h1>
    <div id="map"></div>

    <button id="ride-request-back">Back</button>

    <form action="/ride/requests/create/submit" method="POST">
        @csrf
        <p><strong><span id="ride-request-destination">{{$ride->ride_name}}</span></strong></p>
        <p><strong>Description: </strong><span id="ride-request-description">{{$ride->description}}</span></p>
        <!-- <p><strong>Currently on: </strong><span id="ride-request-location"></span></p> -->
        
        <p>Click on the map to choose your ride destination.</p>

        <p>From your location: <span id="ride-request-from-label">Retrieving location...</span></p>
        <p>To location: <span id="ride-request-to-label">N/A</span></p>

        <input type="number" name="ride_id" id="ride-request-ride-id" value="{{$ride->id}}" hidden value="{{old('ride_id')}}">
        <input type="number" name="destination_id" id="ride-request-destination-id" hidden value="{{old('destination_id')}}">

        <input type="text" name="from_latitude" id="ride-request-from-latitude" hidden value="{{old('from_latitude')}}">
        <input type="text" name="from_longitude" id="ride-request-from-longitude" hidden value="{{old('from_longitude')}}">

        <input type="text" name="to_latitude" id="ride-request-to-latitude" hidden value="{{old('to_latitude')}}">
        <input type="text" name="to_longitude" id="ride-request-to-longitude" hidden value="{{old('to_longitude')}}">

        <label for="pickup_at">To location / Pickup At:</label>
        <input type="text" name="pickup_at" id="ride-request-pickup-at" required value="{{old('pickup_at')}}"></input>

        <label for="price">Price: &#x20B1;<span id="price-value"></span></label>
        <input type="text" name="price" id="ride-request-price" hidden value="{{old('price')}}">

        <label for="time">Pickup Time:</label>
        <input type="time" name="time" id="ride-request-time" required value="{{old('time')}}"></input>

        <label for="message">Message (Optional):</label>
        <textarea name="message" id="ride-request-message" value="{{old('message')}}"></textarea>

        @if ($errors)
            <p>{{$errors}}</p>
        @endif
        <button type="submit">
            <p>{{__('string.submit')}}</p>
        </button>
    </form>

    <script>
        window.fare_rate = {{ $ride->fare_rate ?? 0 }};
        window.minimum_rate = {{ $ride->minimum_rate ?? 0 }};
    </script>
    <script type="module">
        import RideMap from '{{ Vite::asset("resources/js/RideMap.js") }}';
        import CreateRideRequestPage from '{{ Vite::asset("resources/js/CreateRideRequestPage.js") }}';

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

        var page = new CreateRideRequestPage('{{env("APP_URL", "")}}', map, {{$ride->id}});
    </script>
    
@endsection

@push('scripts')
    <script>
        
    </script>
@endpush