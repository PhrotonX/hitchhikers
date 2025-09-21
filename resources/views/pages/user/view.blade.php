@extends('layouts.app')
@section('content')
    <h1>{{$user->getFullName()}}</h1>
    <p><strong>Date joined: </strong>{{$user->created_at}}</p>

    @auth
        @if ($user->id == Auth::user()->id)
            <a href="/settings">Settings</a>
        @endif
    @endauth

    
    <h2>Your Vehicles</h2>
    @foreach ($vehicleDrivers ?? null as $vehicleDriver)
        <p>{{$vehicleDriver->vehicle->vehicle_name}} <a href="/vehicle/{{$vehicleDriver->vehicle->id}}/edit">{{__('string.edit')}}</a></p>
    @endforeach
    @auth
        @if ($user->id == Auth::user()->id)
            @if($user->isDriver())
                <a href="/vehicle/create">Create a vehicle</a>
            @endif
            {{-- <a href="/user/vehicle/">Show all vehicles</a> --}}
        @endif
    @endauth
    
@endsection