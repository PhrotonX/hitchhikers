@props([
    'driver_type' => [
        'ordinary_driver',
        'company_driver',
        'student_driver',
    ],
])

@extends('layouts.app')
@section('content')
    <h1>{{__('string.enroll_to_driving_program')}}</h1>
    <form action="/driver/enroll/submit" method="POST">
        @csrf
        <x-input-label>{{__('credentials.driver_account_name')}}</x-input-label>
        <x-text-input
            :name="'driver_account_name'"
            :placeholder="__('credentials.driver_account_name_hint')"
            :value="old('driver_account_name')"
            :required="true"
        />
        <x-input-error :messages="$errors->get('driver_account_name')"/>

        <br>

        <x-input-label>{{__('string.company')}}</x-input-label>
        <x-text-input
            :name="'company'"
            :placeholder="__('string.company')"
            :value="old('company')"
        />
        <x-input-error :messages="$errors->get('string.company')"/>

        <br>

        <x-input-label>{{__('credentials.driver_type')}}</x-input-label>
        <select name="driver_type" required>
            @foreach ($driver_type as $value)
                <option value="{{$value}}" @selected(old('driver_type') == $value)>{{__('credentials.'.$value)}}</option>
            @endforeach
        </select>
        
        <br>

        <strong>Notice:</strong>
        <p>Please verify your driver account enrolling into driving program. Without verification,
            the ability to make rides will be only available within 1 hour.</p>
        
        <button type="submit">{{__('string.submit')}}</button><br>
    </form>
    {{-- @isset($errors)
        <p>{{$errors}}</p>
    @endisset --}}
@endsection