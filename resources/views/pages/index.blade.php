@extends('layouts.app')
<x-map-head/>

@section('content')
    <div id="map"></div>

    <a href="/ride/create">Create a ride</a>
@endsection

@push('scripts')
    <script type="module">
        import RideMap from '{{ Vite::asset("resources/js/RideMap.js") }}';

        var map = new RideMap('map', '{{env("NOMINATIM_URL", "")}}', '{{env("APP_URL", "")}}');
        map.setMarkerIcon('{{Vite::asset("resources/img/red_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        // map.getMap().center = [15.038880837376297, 120.6808276221496];
        // map.getMap().zoom = 13;
        // map.onMapClick(function(marker, e, data){
        //     // const parser = new DOMParser();
        //     // const xmlDoc = parser.parseFromString(data, 'text/xml');
        
        //     // console.log(xmlDoc);

        //     // console.log(data);
        // })
    </script>
@endpush