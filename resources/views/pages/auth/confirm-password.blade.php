@extends('layouts.app')

@push('head')
    <x-auth-styles />
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1><i class="fas fa-shield-halved"></i> {{__('passwords.confirm_password')}}</h1>
            <p>{{ __('passwords.secure_area_of_app') }}</p>
        </div>

        @if($errors->any())
            <div class="error-message">
                @foreach($errors->all() as $error)
                    <div><i class="fas fa-exclamation-circle"></i> {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="input-group">
                <label><i class="fas fa-lock"></i> {{__('credentials.password')}}</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password">
            </div>

            <button type="submit" class="auth-btn">
                <i class="fas fa-check"></i> {{ __('string.confirm') }}
            </button>
        </form>
    </div>
</div>
@endsection
