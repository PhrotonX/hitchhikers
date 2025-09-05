{{-- @extends('layouts.app')
@section('content')
    <form action="user/{{Auth::user()}}/edit" method="POST">
        @method('POST')
        @csrf
        <label>First Name</label>
        <input type="text" name="first_name" required><br>
        <label>Middle Name</label>
        <input type="text" name="middle_name"><br>
        <label>Last Name</label>
        <input type="text" name="last_name" required><br>
        <label>Ext. Name</label>
        <input type="text" name="ext_name"><br>
        <label>Gender</label>
        <select name="gender" required>
            <option value="male">{{__('gender.male')}}</option>
            <option value="female">{{__('gender.female')}}</option>
        </select><br>
        <label>Birthdate</label>
        <input type="date" name="birthdate" required><br>
        <label>Email</label>
        <input type="email" name="email" required><br>
        <label>Phone</label>
        <input type="phone" name="phone" required><br>
        <button type="submit">Submit</button><br>
    </form>
    @isset($errors)
        <p>{{$errors}}</p>
    @endisset
@endsection --}}

@extends('layouts.app')
@section('content')
    <p><strong>Name: </strong>{{Auth::user()->getFullName()}}</p>
    <p><strong>Date joined: </strong>{{Auth::user()->created_at}}</p>

    
@endsection