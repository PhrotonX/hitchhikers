@extends('layouts.app')
@section('content')
    <h1>{{Auth::user()->getFullName()}}</h1>
    <p><strong>Date joined: </strong>{{Auth::user()->created_at}}</p>

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
@endsection