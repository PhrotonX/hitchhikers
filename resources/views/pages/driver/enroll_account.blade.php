@extends('layouts.app')
@section('content')
    <h1>Enroll to Driving Program</h1>
    <form action="#" method="POST">
        @csrf
        <x-input-label>{{__('credentials.driver_account_name')}}</x-input-label>
        <x-text-input
            :name="'driver_account_name'"
            :placeholder="{{__('credentials.driver_account_name_hint')}}"
            :value="{{old('driver_account_name')}}"
            :required="true"
        />
        <x-input-error :messages="$errors->get('driver_account_name')">
        <button type="submit">Submit</button><br>
    </form>
    @isset($errors)
        <p>{{$errors}}</p>
    @endisset

    @include('pages.user.edit-password')
@endsection