@extends('layouts.app')
@section('content')
    <h1>{{__('string.create_vehicle')}}</h1>
    <form action="/vehicle/create/submit" method="POST">
        @csrf
        <x-input-label>{{__('string.plate_number')}}</x-input-label>
        <x-text-input
            :name="'plate_number'"
            :placeholder="__('string.plate_number_placeholder')"
            :value="old('plate_number')"
            :required="true"
        />
        <x-input-error :messages="$errors->get('plate_number')"/>

        <br>
        
        <x-input-label>{{__('string.vehicle_name')}}</x-input-label>
        <x-text-input
            :name="'vehicle_name'"
            :placeholder="__('string.vehicle_name_placeholder')"
            :value="old('vehicle_name')"
            :required="true"
        />
        <x-input-error :messages="$errors->get('vehicle_name')"/>

        <br>

        <x-input-label>{{__('string.vehicle_model')}}</x-input-label>
        <x-text-input
            :name="'vehicle_model'"
            :placeholder="__('string.vehicle_model_placeholder')"
            :value="old('vehicle_model')"
            :required="true"
        />
        <x-input-error :messages="$errors->get('vehicle_model')"/>

        <br>

        <x-input-label>{{__('string.brand')}}</x-input-label>
        <x-text-input
            :name="'vehicle_brand'"
            :placeholder="__('string.brand_placeholder')"
            :value="old('vehicle_brand')"
            :required="true"
        />
        <x-input-error :messages="$errors->get('vehicle_brand')"/>

        <br>

        <x-input-label>{{__('string.capacity')}}</x-input-label>
        <x-text-input
            :name="'capacity'"
            :placeholder="__('string.capacity_placeholder')"
            :value="old('capacity')"
            :required="true"
            :type="'number'"
        />
        <x-input-error :messages="$errors->get('capacity')"/>

        <br>

        <x-input-label>{{__('string.color')}}</x-input-label>
        <x-text-input
            :name="'color'"
            :placeholder="__('string.color_placeholder')"
            :value="old('color')"
            :required="true"
            :type="'color'"
        />
        <x-input-error :messages="$errors->get('color')"/>

        <br>

        <x-input-label>{{__('string.vehicle_type')}}</x-input-label>
        <select name="type" title="{{__('string.vehicle_type')}}" required>
            <option disabled selected>{{__('string.vehicle_type_placeholder')}}</option>
            @foreach (__('vehicle_type') as $key => $value)
                <option value="{{$key}}" @selected(old('type') == $key)>{{$value}}</option>
            @endforeach
        </select>
        <br>

        <x-input-error :messages="$errors->get('type')"/>
        
        <!-- Hidden fields for location -->
        <input type="hidden" name="latitude" id="vehicle-latitude" value="{{old('latitude')}}">
        <input type="hidden" name="longitude" id="vehicle-longitude" value="{{old('longitude')}}">

        @if (isset($errors))
            <p>{{$errors}}</p>
        @endif

        <button type="submit">Submit</button>
    </form>
@endsection

@push('scripts')
<script>
    // Get current location and set it in hidden fields
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.getElementById('vehicle-latitude').value = position.coords.latitude;
                document.getElementById('vehicle-longitude').value = position.coords.longitude;
                console.log('Vehicle location set:', position.coords.latitude, position.coords.longitude);
            },
            function(error) {
                console.warn('Geolocation error:', error.message);
                // Set default location if geolocation fails (e.g., Manila, Philippines)
                document.getElementById('vehicle-latitude').value = 14.5995;
                document.getElementById('vehicle-longitude').value = 120.9842;
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    } else {
        console.warn('Geolocation not supported');
        // Set default location
        document.getElementById('vehicle-latitude').value = 14.5995;
        document.getElementById('vehicle-longitude').value = 120.9842;
    }
</script>
@endpush