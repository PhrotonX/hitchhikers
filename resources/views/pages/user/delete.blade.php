@props([
    'gender' => [
        'male' => __('gender.male'),
        'female' => __('gender.female'),
    ],
])

@extends('layouts.app')
@section('content')
<h1>
        {{ __('credentials.delete_account') }}
    </h1>

    <p>
        {{ __('credentials.delete_account_info') }}
    </p>
    <form action="{{ route('user.destroy', ['user' => Auth::user()->id]) }}" method="POST">
        @csrf
        @method('DELETE')

        <h2>
            {{ __('credentials.delete_account_confirmation') }}
        </h2>

        <p>{{ __('credentials.delete_account_warning') }}</p>

        <div>
            <x-input-label for="password" value="{{ __('credentials.password') }}"/>

            <x-text-input
                id="password"
                name="password"
                type="password"
                placeholder="{{ __('credentials.password') }}"
            />

            <x-input-error :messages="$errors->get('password')"/>
        </div>

        <button type="button">
            <p>Cancel</p>
        </button>
        <button type="submit">
            <p>Delete</p>
        </button>
    </form>
@endsection