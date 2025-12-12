@extends('layouts.app')

@push('head')
    <x-auth-styles />
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1><i class="fas fa-lock-open"></i> {{__('credentials.reset_password')}}</h1>
            <p>Enter your new password</p>
        </div>

        @if($errors->any())
            <div class="error-message">
                <strong><i class="fas fa-exclamation-circle"></i> Errors:</strong>
                <ul style="margin: 8px 0 0 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="input-group">
                <label><i class="fas fa-envelope"></i> {{__('credentials.email')}}</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            </div>

            <div class="input-group">
                <label><i class="fas fa-lock"></i> {{__('credentials.password')}}</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Enter new password">
            </div>

            <div class="input-group">
                <label><i class="fas fa-lock"></i> {{__('passwords.password_confirmation')}}</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm new password">
            </div>

            <button type="submit" class="auth-btn">
                <i class="fas fa-check-circle"></i> {{ __('credentials.reset_password') }}
            </button>
        </form>
    </div>
</div>
@endsection
