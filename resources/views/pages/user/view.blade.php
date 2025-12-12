@extends('layouts.app')
@section('content')
    <h1>{{$user->getFullName()}}</h1>
    <p><strong>Date joined: </strong>{{$user->created_at}}</p>

    @auth
        @if ($user->id == Auth::user()->id)
            <a href="/settings">Settings</a> |
            <a href="/user/{{$user->id}}/profile-pictures">Manage Profile Pictures</a>
        @endif
    @endauth

    @if ($user->isDriver())
        @auth
            <h2>Your Vehicles</h2>
        @else
            <h2>Vehicles</h2>
        @endauth
        @foreach ($vehicleDrivers ?? null as $vehicleDriver)
            <p><a href="/vehicle/{{$vehicleDriver->vehicle->id}}">{{$vehicleDriver->vehicle->vehicle_name}}</a> <a href="/vehicle/{{$vehicleDriver->vehicle->id}}/edit">{{__('string.edit')}}</a></p>
            <form action="/vehicle/{{$vehicleDriver->vehicle->id}}/delete" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">{{__('string.delete')}}</button>
            </form>
            
        @endforeach
        @auth
            @if ($user->id == Auth::user()->id)
                @if($user->isDriver())
                    <a href="/vehicle/create">Create a vehicle</a>
                @endif
                {{-- <a href="/user/vehicle/">Show all vehicles</a> --}}
            @endif
        @endauth
    @endif
    

    @if (isset($status))
        <x-status>{{$status}}</x-status>
        <p>{{$status}}</p> 
    @endif
    
@endsection