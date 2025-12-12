@extends('layouts.app')

@push('head')
    <x-auth-styles />
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1><i class="fas fa-envelope-circle-check"></i> Verify Your Email</h1>
            <p>{{ __('auth.verify_email_message') }}</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="success-message">
                <i class="fas fa-check-circle"></i> {{ __('auth.email_verification_sent') }}
            </div>
        @endif

        <div style="display: flex; gap: 10px; flex-direction: column;">
            <button type="button" class="auth-btn" id="verification-send-btn">
                <i class="fas fa-paper-plane"></i> {{ __('auth.resend_verification_email') }}
            </button>
            <button type="button" class="auth-btn auth-btn-secondary" id="logout-btn">
                <i class="fas fa-sign-out-alt"></i> {{__('credentials.log_out')}}
            </button>
        </div>

        <form method="POST" action="{{route('logout')}}" id="logout-form" style="display: none;">
            @csrf
        </form>
        <form method="POST" action="{{ route('verification.send') }}" id="verification-send-form" style="display: none;">
            @csrf
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('logout-btn').addEventListener('click', function(){
        document.getElementById('logout-form').submit();
    });
    document.getElementById('verification-send-btn').addEventListener('click', function(){
        document.getElementById('verification-send-form').submit();
    });
</script>
@endpush
