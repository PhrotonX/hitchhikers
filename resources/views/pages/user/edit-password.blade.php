{{-- @props([
    'gender' => [
        'male' => __('gender.male'),
        'female' => __('gender.female'),
    ],
])

@extends('layouts.app')
@section('content') --}}
<header>
    <h2>
        {{ __('credentials.update_password') }}
    </h2>

    <p>
        {{ __('passwords.update_password_msg') }}
    </p>
</header>

<form action="{{ route('password.update') }}" method="POST">
    @csrf
    @method('PUT')
    
    <div>
        <x-input-label for="update_password_current_password" :value="__('credentials.current_password')" />
        <x-text-input id="update_password_current_password" name="current_password" type="password"  autocomplete="current-password" />
        <x-input-error :messages="$errors->get('current_password')"/>
    </div>

    <div>
        <x-input-label for="update_password_password" :value="__('credentials.new_password')" />
        <x-text-input id="update_password_password" name="password" type="password" autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password')"/>
    </div>

    <div>
        <x-input-label for="update_password_password_confirmation" :value="__('passwords.password_confirmation')" />
        <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password_confirmation')"/>
    </div>

    <div>
        <button type="submit">
            {{ __('string.save') }}
        </button>

        @if (session('status') === 'password-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
            >{{ __('Saved.') }}</p>
        @endif
    </div>
</form>
{{-- @endsection --}}