@props([
    'gender' => [
        'male' => __('gender.male'),
        'female' => __('gender.female'),
    ],
])

@extends('layouts.app')
@section('content')
    <form action="register" method="POST">
        @method('POST')
        @csrf
        <label>First Name</label>
        <input type="text" name="first_name" required value="{{old('first_name')}}"><br>
        <label>Middle Name</label>
        <input type="text" name="middle_name" value="{{old('middle_name')}}"><br>
        <label>Last Name</label>
        <input type="text" name="last_name" required value="{{old('last_name')}}"><br>
        <label>Ext. Name</label>
        <input type="text" name="ext_name" value="{{old('ext_name')}}"><br>
        <label>Gender</label>
        <select name="gender" required>
            @foreach ($gender as $key => $value)
                @if (old('gender') == $key)
                    <option value="{{$key}}" selected >{{$value}}</option>
                @else
                    <option value="{{$key}}">{{$value}}</option>
                @endif
            @endforeach
        </select><br>
        
        <label>Birthdate</label>
        <input type="date" name="birthdate" required value="{{old('birthdate')}}"><br>
        <label>Email</label>
        <input type="email" name="email" required value="{{old('email')}}"><br>
        <label>Phone</label>
        <input type="phone" name="phone" required value="{{old('phone')}}"><br>
        <label>Password</label>
        <input type="password" name="password" required value="{{old('password')}}"><br>
        <label>Password Confirmation</label>
        <input type="password" name="password_confirmation" required value="{{old('password_confirmation')}}"><br>
        <button type="submit">Submit</button><br>
    </form>
    @isset($errors)
        <p>{{$errors}}</p>
    @endisset
@endsection