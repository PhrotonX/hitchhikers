@extends('layouts.app')

{{-- @push('head')
    @vite(['resources/css/form.css'])
@endpush --}}

@section('content')
<main>
    <div>
        {{ __('passwords.secure_area_of_app') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="form">
        @csrf
        {{-- <x-form-section-content :title="__('passwords.confirm_password')"> --}}
            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('credentials.password')" />

                <x-text-input 
                            id="password"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')"/>
            </div>

            <button type="submit">
                <p>{{ __('string.confirm') }}</p>
            </button>
        {{-- </x-form-section-content> --}}
    </form>
</main>
@endsection
