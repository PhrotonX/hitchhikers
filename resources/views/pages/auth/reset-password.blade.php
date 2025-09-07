@extends('layouts.app')

{{-- @push('head')
    @vite(['resources/css/form.css'])
@endpush --}}

@section('content')
<main>
    <form method="POST" action="{{ route('password.store') }}" class="form">
        @csrf
        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <h1>{{__('credentials.reset_password')}}</h1>
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('credentials.email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <br>
        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('credentials.password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <br>
        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('passwords.password_confirmation')" />
            <x-text-input id="password_confirmation"
                        type="password"
                        name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        
        @isset($errors)
            <p>{{$errors}}</p>
        @endisset

        <div class="form-buttons">
            <button type="submit">
                <p>{{ __('credentials.reset_password') }}</p>
            </button>
        </div>
    </form>
</main>
@endsection
