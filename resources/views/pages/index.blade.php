@extends('layouts.app')
<x-map-head/>

@section('content')
    <div id="map"></div>

    <a href="/ride/create">Create a ride</a>

    <div id="driving-mode">
        <button type="submit">Start driving mode</button>
        {{-- @TODO: Insert a dropdown menu here to be able to choose a vehicle to begin with. --}}
        {{-- Use JavaScript to perform the driving mode. --}}
    </div>
    
    
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
    </script>
@endpush