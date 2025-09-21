@extends('layouts.app')
@section('content')
    <h1>{{__('string.create_ride')}}</h1>
    
    <x-input-label>{{__('string.ride_name')}}</x-input-label>
    <x-text-input
        :name="'ride_name'"
        :placeholder="__('string.ride_name_placeholder')"
        :value="old('ride_name')"
        :required="true"
    />
    <x-input-error :messages="$errors->get('ride_name')"/>

    <x-input-label>{{__('string.fare_rate')}}</x-input-label>
    <x-text-input
        :name="'fare_rate'"
        :placeholder="__('string.fare_rate_placeholder')"
        :value="old('fare_rate')"
        :required="true"
    />
    <x-input-error :messages="$errors->get('fare_rate')"/>

    <x-input-label>{{__('string.vehicle')}}</x-input-label>
    <x-text-input
        :name="'vehicle_id'"
        :placeholder="__('string.vehicle_placeholder')"
        :value="old('vehicle_id')"
        :required="true"
    />
    <x-input-error :messages="$errors->get('vehicle_id')"/>

    <h2>{{__('string.destinations')}}</h2>

    {{-- Must be able to add or remove an address. --}}
    {{-- Must be draggable. Each drag should change the destination value based on their positions. --}}
    {{-- <x-input-label>{{__('string.unit_no')}}</x-input-label>
    <x-text-input
        :name="'unit_no'"
        :placeholder="__('string.unit_no_placeholder')"
        :value="old('unit_no')"
        :required="true"
    />
    <x-input-error :messages="$errors->get('unit_no')"/>

    <x-input-label>{{__('string.unit_no')}}</x-input-label>
    <x-text-input
        :name="'unit_no'"
        :placeholder="__('string.unit_no_placeholder')"
        :value="old('unit_no')"
        :required="true"
    />
    <x-input-error :messages="$errors->get('unit_no')"/> --}}

@endsection