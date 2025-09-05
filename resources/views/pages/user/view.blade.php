@extends('layouts.app')
@section('content')
    <h1>{{Auth::user()->getFullName()}}</h1>
    <p><strong>Date joined: </strong>{{Auth::user()->created_at}}</p>

    <a href="/user/{{Auth::user()->id}}/edit">Edit</a>
@endsection