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
    <a href="/vehicle/create">Create a vehicle</a>
    
@endsection