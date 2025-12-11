@extends('layouts.app')

@section('content')
    <div class="ride-delete-container">
        <h1>{{ __('string.delete_ride') }}</h1>

        <div class="alert alert-warning">
            <strong>{{ __('string.warning') }}!</strong> {{ __('string.delete_ride_warning') }}
        </div>

        <div class="ride-details">
            <h2>{{ __('string.ride_information') }}</h2>
            <p><strong>{{ __('string.ride_name') }}:</strong> {{ $ride->ride_name }}</p>
            <p><strong>{{ __('string.fare_rate') }}:</strong> {{ $ride->fare_rate }}</p>
            <p><strong>{{ __('string.status') }}:</strong> {{ $ride->status ?: __('string.not_set') }}</p>
            <p><strong>{{ __('string.created_at') }}:</strong> {{ $ride->created_at->format('M d, Y h:i A') }}</p>
        </div>

        <p class="confirmation-text">
            {{ __('string.delete_ride_confirmation', ['ride_name' => $ride->ride_name]) }}
        </p>

        <div class="delete-actions">
            <form action="{{ route('ride.destroy', $ride->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('string.are_you_sure') }}')">
                    {{ __('string.yes_delete') }}
                </button>
            </form>

            <a href="{{ route('ride.show', $ride->id) }}" class="btn btn-secondary">
                {{ __('string.cancel') }}
            </a>
        </div>
    </div>

    <style>
        .ride-delete-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .alert-warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
        }

        .ride-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .confirmation-text {
            font-size: 16px;
            margin: 20px 0;
        }

        .delete-actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
@endsection
