@extends('layouts.app')
{{-- 
@push('head')
    @vite(['resources/css/form.css'])
@endpush --}}

@section('content')
<main>
    <!-- Session Status -->
    {{-- <x-status :class="'auth-session-status'" :status="session('status')" /> --}}

    <h1>{{__('passwords.password_reset_form')}}</h1>
    <form method="POST" action="{{ route('password.email') }}">
        <p>{{ __('passwords.password_reset_form_text') }}</p>
        @csrf
        <label>{{__('credentials.email')}}</label>
        <input
            placeholder="{{__('credentials.email_hint')}}"
            id="email"
            name="email"
            value="{{old('email')}}"
            autocomplete="email"
            required
            autofocus
            type="email"
        >

        {{-- <x-input-error :messages="$errors->get('email')" />     --}}
            
        <button type="submit">
            <p>{{ __('credentials.email_password_reset_link') }}</p>
        </button>
    </form>
</main>
@endsection