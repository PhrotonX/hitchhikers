@extends('layouts.app')
@section('content')
    <h1>{{Auth::user()->getFullName()}}</h1>
    <p><strong>Date joined: </strong>{{Auth::user()->created_at}}</p>

    @auth
        <a href="/settings">Settings</a>
    @endauth
    
@endsection