@extends('layouts.app')

@push('head')
    <x-auth-styles />
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1><i class="fas fa-key"></i> {{ __('passwords.password_reset_form') }}</h1>
            <p>{{ __('passwords.password_reset_form_text') }}</p>
        </div>

        @if (session('status'))
            <div class="success-message">
                <i class="fas fa-check-circle"></i> {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="error-message">
                @foreach($errors->all() as $error)
                    <div><i class="fas fa-exclamation-circle"></i> {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="input-group">
                <label><i class="fas fa-envelope"></i> {{__('credentials.email')}}</label>
                <input type="email" name="email" value="{{old('email')}}" placeholder="{{__('credentials.email_hint')}}" required autofocus>
            </div>

            <button type="submit" class="auth-btn">
                <i class="fas fa-paper-plane"></i> {{ __('credentials.email_password_reset_link') }}
            </button>

            <div class="auth-footer">
                Remember your password? <a href="/login">Sign in</a>
            </div>
        </form>
    </div>
</div>
@endsection
