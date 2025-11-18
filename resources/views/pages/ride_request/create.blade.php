@extends('layouts.app')

<x-map-head/>

@push('head')
    @vite('resources/js/RideMap.js');
@endpush

@section('content')
    <h1>Make a Ride Request</h1>
    <div id="map"></div>

    <button id="ride-request-back">Back</button>

    <form>
        <p><strong><span id="ride-request-destination">{{$ride->ride_name}}</span></strong></p>
        <p><strong>Description: </strong><span id="ride-request-description">{{$ride->description}}</span></p>
        <!-- <p><strong>Currently on: </strong><span id="ride-request-location"></span></p> -->
        
        <p>Click on the map to choose your ride destination.</p>
        <label for="pickup_at">Pickup At:</label>
        <input type="text" name="pickup_at" id="ride-request-pickup-at"></input>

        <label for="time">Pickup Time:</label>
        <input type="time" name="time" id="ride-request-time"></input>

        <label for="message">Message (Optional):</label>
        <textarea name="message" id="ride-request-message"></textarea>

        @if ($errors)
            <p>{{$errors}}</p>
        @endif
        <button type="submit">
            <p>{{__('string.submit')}}</p>
        </button>
    </form>

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
        map.detectLocation();

        var page = new CreateRideRequestPage('{{env("APP_URL", "")}}', map, {{$ride->id}});
    </script>
    
@endsection

@push('scripts')
    <script>
        
    </script>
@endpush