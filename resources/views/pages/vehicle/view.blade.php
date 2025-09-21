@extends('layouts.app')
@section('content')
    <h1>{{$vehicle->vehicle_name}}</h1>
    <p>{{$vehicle->brand}}</p>
    <p>{{$vehicle->color}}</p>
    <p>{{$vehicle->vehicle_model}}</p>
@endsection