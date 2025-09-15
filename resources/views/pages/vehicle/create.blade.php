@extends('layouts.app')
@section('content')
    <h1>Create Vehicle</h1>
    <form action="#" method="POST">
        @csrf
        <x-input-label>{{__('string.vehicle_name')}}</x-input-label>
        <x-text-input
            :name="'vehicle_name'"
            :placeholder="__('string.vehicle_name_placeholder')"
            :value="old('vehicle_name')"
        />
        <x-input-error :messages="$error->get('vehicle_name')"/>

        <button type="submit">Submit</button>
    </form>
@endsection