@extends('layouts.app')

<x-map-head/>

{{-- @push('head')
    @vite('resources/js/Map.js');
@endpush --}}

@section('content')
    <h1>Make a Ride Request</h1>
    <div id="map"></div>

    <form>
        <button id="ride-request-close">Close</button>
        <h2>Make a Ride Request</h2>
        <p><strong>Destination: </strong><span id="ride-request-destination"></span></p>
        <p><strong>Description: </strong><span id="ride-request-description"></span></p>
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

    
    
@endsection

@push('scripts')
    <script>
        
    </script>
@endpush