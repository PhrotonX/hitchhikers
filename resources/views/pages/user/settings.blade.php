@extends('layouts.app')
@section('content')
    {{-- Should display a split-screen interface wher ethe left side is the navigation pane and the right
    side is the settings content. Must be loaded through JavaScript fetch API. --}}
    {{-- For smaller screens, the user settings should be displayed similarly to a typical settings app
    in phones. Still, must be loaded through JavaScript API. --}}
    @auth
        <a href="/user/{{Auth::user()->id}}/edit">Edit</a>
        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
            <div>
                <p>
                    {{ __('credentials.email_unverified_msg') }}
                </p>
                <button type="button" id="verify-email-btn" onclick="window.location.href='{{route('verification.notice')}}'">
                    <p>{{ __('credentials.verify_email') }}</p>
                </button>
            </div>
        @endif

        <a href="/user/{{Auth::user()->id}}/delete">Delete</a><br>
        <a href="/driver/enroll">Enroll to Driving Program</a>
    @endauth
    
@endsection