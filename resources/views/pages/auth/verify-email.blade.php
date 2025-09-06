@extends('layouts.app')

{{-- Not implemented: --}}
{{-- @push('head')
    @vite(['resources/css/form.css'])
@endpush --}}

@section('content')
<main>
    <div>
        <x-form-section-content :title="__('auth.verify_email')">
            <p>{{ __('auth.verify_email_message') }}</p>

            @if (session('status') == 'verification-link-sent')
                <p>
                    {{ __('auth.email_verification_sent') }}
                </p>
            @endif
        </x-form-section-content>

        <div>
            <button type="button" id="logout-btn">
                <p>{{__('credentials.log_out')}}</p>
            </button>
            <button type="button" id="verification-send-btn">
                <p>{{ __('auth.resend_verification_email') }}</p>
            </button>
        </div>
        <form method="POST" action="{{route('logout')}}" id="logout-form">
            @csrf
        </form>
        <form method="POST" action="{{ route('verification.send') }}" id="verification-send-form">
            @csrf
        </form>
    </div>
</main>
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