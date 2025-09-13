@extends('layouts.app')
@section('content')
    {{-- Should display a split-screen interface wher ethe left side is the navigation pane and the right
    side is the settings content. Must be loaded through JavaScript fetch API. --}}
    {{-- For smaller screens, the user settings should be displayed similarly to a typical settings app
    in phones. Still, must be loaded through JavaScript API. --}}
    @auth
        <a href="/user/{{Auth::user()->id}}/edit">Edit Account</a>
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
    @endauth
    
    @if ($driverAccount != null)
        <p><strong>Driver Account Name: </strong>{{$driverAccount->driver_account_name}}</p>
        <p><strong>Driver Type: </strong>{{__('driver_type.' . $driverAccount->driver_type)}}</p>
        <p><strong>Company: </strong>{{$driverAccount->company}}</p>
    @endif
    

    @auth
        <a href="/user/{{Auth::user()->id}}/delete">Delete Account</a><br>
        @if ($driverAccount == null)
            <a href="/driver/enroll">Enroll to Driving Program</a>
        @else
            <a href="/driver/{{$driverAccount->id}}/edit">Edit Driver Account</a>
            <form action="/driver/{{$driverAccount->id}}/leave" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Leave Driving Program</button>
            </form>
        @endif
    @endauth
    
@endsection