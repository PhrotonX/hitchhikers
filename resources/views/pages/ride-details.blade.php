@extends('layouts.app')

@push('head')
    <style>
        .ride-details-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 2rem;
            color: var(--text-dark, #333);
            margin-bottom: 0.5rem;
        }

        .ride-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .ride-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .ride-status {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .status-ongoing {
            background: #cfe2ff;
            color: #084298;
        }

        .ride-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-block {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
        }

        .info-label {
            font-size: 0.85rem;
            color: #999;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .info-value {
            font-size: 1.1rem;
            color: var(--text-dark, #333);
            font-weight: 500;
        }

        .location-info {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .location-icon {
            font-size: 1.5rem;
            color: var(--primary, #007bff);
            margin-top: 0.25rem;
        }

        .location-text h4 {
            margin: 0 0 0.25rem 0;
            color: var(--text-dark, #333);
        }

        .location-text p {
            margin: 0;
            color: #666;
            font-size: 0.95rem;
        }

        .passenger-info {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .passenger-photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            background: #ddd;
        }

        .passenger-details h4 {
            margin: 0 0 0.25rem 0;
            color: var(--text-dark, #333);
        }

        .passenger-details p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }

        .rating {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            margin-top: 0.5rem;
        }

        .star {
            color: #ffc107;
        }

        .fare-breakdown {
            background: white;
            border: 2px solid #f0f0f0;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .fare-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .fare-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
            padding-top: 0.75rem;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--text-dark, #333);
        }

        .fare-label {
            color: #666;
        }

        .fare-amount {
            color: var(--text-dark, #333);
            font-weight: 500;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
        }

        .btn-primary {
            background: var(--primary, #007bff);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark, #0056b3);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .ride-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .ride-info-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
@endpush

@section('content')
    <div class="ride-details-container">
        <div class="page-header">
            <h1>{{ __('Ride Details') }}</h1>
        </div>

        <div class="ride-card">
            <div class="ride-header">
                <div>
                    <h2 style="margin: 0 0 0.5rem 0; color: var(--text-dark, #333);">{{ __('Ride ID:') }} #{{ $ride->id ?? '12345' }}</h2>
                    <p style="margin: 0; color: #999; font-size: 0.95rem;">{{ __('Date:') }} {{ $ride->created_at->format('M d, Y h:i A') ?? 'Dec 10, 2025 2:30 PM' }}</p>
                </div>
                <span class="ride-status status-completed">{{ __('Completed') }}</span>
            </div>

            <!-- Location Information -->
            <h3 style="margin-bottom: 1rem; color: var(--text-dark, #333);">{{ __('Route') }}</h3>
            <div class="location-info">
                <div class="location-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="location-text">
                    <h4>{{ __('Pickup Location') }}</h4>
                    <p>{{ $ride->pickup_address ?? '123 Main Street, Downtown' }}</p>
                </div>
            </div>

            <div class="location-info">
                <div class="location-icon" style="color: #28a745;">
                    <i class="fas fa-flag-checkered"></i>
                </div>
                <div class="location-text">
                    <h4>{{ __('Dropoff Location') }}</h4>
                    <p>{{ $ride->dropoff_address ?? '456 Oak Avenue, Uptown' }}</p>
                </div>
            </div>

            <!-- Ride Information -->
            <div class="ride-info-grid">
                <div class="info-block">
                    <div class="info-label">{{ __('Distance') }}</div>
                    <div class="info-value">{{ $ride->distance ?? '8.2' }} km</div>
                </div>

                <div class="info-block">
                    <div class="info-label">{{ __('Duration') }}</div>
                    <div class="info-value">{{ $ride->duration ?? '22' }} minutes</div>
                </div>

                <div class="info-block">
                    <div class="info-label">{{ __('Passengers') }}</div>
                    <div class="info-value">{{ $ride->passengers ?? '2' }} {{ __('people') }}</div>
                </div>
            </div>

            <!-- Passenger Information -->
            <h3 style="margin-bottom: 1rem; margin-top: 2rem; color: var(--text-dark, #333);">{{ __('Passenger') }}</h3>
            <div class="passenger-info">
                <img src="https://via.placeholder.com/60" alt="Passenger" class="passenger-photo">
                <div class="passenger-details">
                    <h4>{{ $ride->passenger_name ?? 'John Doe' }}</h4>
                    <p>{{ $ride->passenger_phone ?? '+1 (555) 123-4567' }}</p>
                    <div class="rating">
                        <span class="star"><i class="fas fa-star"></i></span>
                        <span class="star"><i class="fas fa-star"></i></span>
                        <span class="star"><i class="fas fa-star"></i></span>
                        <span class="star"><i class="fas fa-star"></i></span>
                        <span class="star"><i class="fas fa-star-half-alt"></i></span>
                        <span style="color: #666; font-size: 0.9rem;">(4.5)</span>
                    </div>
                </div>
            </div>

            <!-- Fare Breakdown -->
            <h3 style="margin-bottom: 1rem; margin-top: 2rem; color: var(--text-dark, #333);">{{ __('Fare Details') }}</h3>
            <div class="fare-breakdown">
                <div class="fare-row">
                    <span class="fare-label">{{ __('Base Fare') }}</span>
                    <span class="fare-amount">${{ $ride->base_fare ?? '2.50' }}</span>
                </div>

                <div class="fare-row">
                    <span class="fare-label">{{ __('Distance') }} ({{ $ride->distance ?? '8.2' }} km @ $1.50/km)</span>
                    <span class="fare-amount">${{ $ride->distance_fare ?? '12.30' }}</span>
                </div>

                <div class="fare-row">
                    <span class="fare-label">{{ __('Time') }} ({{ $ride->duration ?? '22' }} min @ $0.45/min)</span>
                    <span class="fare-amount">${{ $ride->time_fare ?? '9.90' }}</span>
                </div>

                <div class="fare-row">
                    <span class="fare-label">{{ __('Discount') }}</span>
                    <span class="fare-amount" style="color: #28a745;">-${{ $ride->discount ?? '1.00' }}</span>
                </div>

                <div class="fare-row">
                    <span>{{ __('Total Fare') }}</span>
                    <span>${{ $ride->total_fare ?? '23.70' }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="btn btn-secondary" onclick="window.location.href='{{ route('dashboard') }}'">{{ __('Back to Dashboard') }}</button>
            </div>
        </div>
    </div>
@endsection
