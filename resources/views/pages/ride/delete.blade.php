@extends('layouts.app')

@push('head')
    @vite('resources/css/ride/delete.css')
@endpush

@section('content')
    <div class="ride-delete-container">
        <h1>{{ __('string.delete_ride') }}</h1>

        <div class="alert alert-warning">
            <strong>{{ __('string.warning') }}!</strong> {{ __('ride.delete_ride_warning') }}
        </div>

        <div class="ride-details">
            <h2>{{ __('ride.ride_information') }}</h2>
            <p><strong>{{ __('ride.ride_name') }}:</strong> {{ $ride->ride_name }}</p>
            <p><strong>{{ __('ride.fare_rate') }}:</strong> {{ $ride->fare_rate }}</p>
            <p><strong>{{ __('ride.status') }}:</strong> {{ $ride->status ?: __('string.not_set') }}</p>
            <p><strong>{{ __('string.created_at') }}:</strong> {{ $ride->created_at->format('M d, Y h:i A') }}</p>
        </div>

        <p class="confirmation-text">
            {{ __('ride.delete_ride_confirmation', ['ride_name' => $ride->ride_name]) }}
        </p>

        <div class="delete-actions">
            <form action="{{ route('ride.destroy', $ride->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    {{ __('string.yes_delete') }}
                </button>
            </form>

            <a href="{{ route('ride.show', $ride->id) }}" class="btn btn-secondary">
                {{ __('string.cancel') }}
            </a>
        </div>
    </div>
@endsection
