@extends('layouts.app')
@section('content')
    <form action="login" method="POST">
        @csrf
        <label>Email</label>
        <input type="text" name="email">
        <label>Password</label>
        <input type="password" name="password">
        <button type="submit">Submit</button>
    </form>

    <button type="button" onclick="window.location.href='/register'">Create an account</button>
    <button type="button" onclick="window.location.href='/forgot-password'">{{__('credentials.forgot_password')}}</button>

    @isset($errors)
        <p>{{$errors}}</p>
    @endisset
@endsection