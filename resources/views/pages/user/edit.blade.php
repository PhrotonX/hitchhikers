@extends('layouts.app')
@section('content')
    <h1>Edit profile</h1>
    <form action="user/{{Auth::user()}}/edit" method="POST">
        @method('POST')
        @csrf
        <label>First Name</label>
        <input type="text" name="first_name" required value="{{$user->first_name}}"><br>
        <label>Middle Name</label>
        <input type="text" name="middle_name" value="{{$user->middle_name}}"><br>
        <label>Last Name</label>
        <input type="text" name="last_name" required value="{{$user->last_name}}"><br>
        <label>Ext. Name</label>
        <input type="text" name="ext_name" value="{{$user->ext_name}}"><br>
        <label>Gender</label>
        <select name="gender" required>
            <option value="male">{{__('gender.male')}}</option>
            <option value="female">{{__('gender.female')}}</option>
        </select><br>
        <label>Birthdate</label>
        <input type="date" name="birthdate" required value="{{$user->birthdate}}"><br>
        <label>Email</label>
        <input type="email" name="email" required value="{{$user->email}}"><br>
        <label>Phone</label>
        <input type="phone" name="phone" required value="{{$user->phone}}"><br>
        <button type="submit">Submit</button><br>
    </form>
    @isset($errors)
        <p>{{$errors}}</p>
    @endisset
@endsection