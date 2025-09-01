@extends('layouts.app')
@section('content')
    <form action="api/login" method="POST">
        @csrf
        <label>Email</label>
        <input type="text" name="email">
        <label>Password</label>
        <input type="password" name="password">
        <button type="submit">Submit</button>
    </form>
    <button type="button">Create an account</button>
@endsection