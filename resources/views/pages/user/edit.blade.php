@props([
    'gender' => [
        'male' => __('gender.male'),
        'female' => __('gender.female'),
    ],
])

@extends('layouts.app')
@section('content')
    <nav style="margin-bottom: 20px;">
        <a href="/user/{{Auth::user()->id}}">&larr; Back to Profile</a> |
        <a href="/user/{{Auth::user()->id}}/profile-pictures">Manage Profile Picture</a> |
        <a href="/settings">Settings</a>
    </nav>
    <h1>Edit profile</h1>
    <form action="/user/{{Auth::user()->id}}/update" method="POST">
        @method('PATCH')
        @csrf
        <label>First Name</label>
        <input type="text" name="first_name" required value="{{$user->first_name}}"><br>
        <label>Middle Name</label>
        <input type="text" name="middle_name" value="{{$user->middle_name}}"><br>
        <label>Last Name</label>
        <input type="text" name="last_name" required value="{{$user->last_name}}"><br>
        <label>Ext. Name</label>
        <input type="text" name="ext_name" value="{{$user->ext_name}}"><br>
        <label>Gender</label>
        <select name="gender" required>
            @foreach ($gender as $key => $value)
                @if (old('gender') == $key)
                    <option value="{{$key}}" selected >{{$value}}</option>
                @else
                    <option value="{{$key}}">{{$value}}</option>
                @endif
            @endforeach
        </select><br>
        <label>Birthdate</label>
        <input type="date" name="birthdate" required value="{{$user->birthdate}}"><br>
        <label>Email</label>
        <input type="email" name="email" required value="{{$user->email}}"><br>
        <label>Phone</label>
        <input type="phone" name="phone" required value="{{$user->phone}}"><br>
        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
            <div>
                <p>
                    {{ __('credentials.email_unverified_msg') }}
                </p>
                <button type="button" id="verify-email-btn" onclick="window.location.href='{{route('verification.send')}}'">
                    <p>{{ __('credentials.email_resend_verification') }}</p>
                </button>

                @if (session('status') === 'verification-link-sent')
                    <p>{{ __('credentials.verification_link_sent') }}</p>
                @endif
            </div>
        @endif
        <button type="submit">Submit</button><br>
    </form>
    @isset($errors)
        <p>{{$errors}}</p>
    @endisset

    @include('pages.user.edit-password')
@endsection
{{-- 
@push('scripts')
    <script>
        document.getElementById('verify-email-btn').addEventListener('click', () => {
            fetch('{{route("verification.send")}}')
                .then((response) => {
                    document.getElementById('verify-email-btn').innerHTML = "<p>Email verification sent!</p>";
                    console.log('Response on verify-email-btn' + response);
                })
                .then((data) => {
                    console.log('Data on verify-email-btn' + data);
                })
                .catch((error) => {
                    console.log("Error on verify-email-btn" + error);
                });
        })
    </script>
@endpush --}}