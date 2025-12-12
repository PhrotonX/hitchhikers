@extends('layouts.app')

@section('content')
    <div class="main-layout">

        <!--MAIN SIDE SECTION-->
        <aside class="mlay-side">
            
        </aside>

        <main class="main-content">

            <section class="top-section">
                <p><h4>{{ __('Share Your Journey, Save Your Costs') }}</h4></p>
                <p>{{ __('Find rides, connect with drivers, and travel affordably.') }}</p>
            </section>

            <section class="mid-section">
                <p><h3>{{ __('Upcoming Rides') }}</h3></p>
                @if(Auth::user()->rides->count() > 0)
                    <div class="rides-list">
                        @foreach(Auth::user()->rides as $ride)
                            <div class="ride-card">
                                <h4>{{ $ride->destination }}</h4>
                                <p>{{ __('Departure:') }} {{ $ride->departure_time }}</p>
                                <p>{{ __('Driver:') }} {{ $ride->driver->name ?? 'N/A' }}</p>
                                <a href="{{ url('ride/' . $ride->id) }}" class="btn-secondary">{{ __('View Details') }}</a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>{{ __('No upcoming rides. ') }}<a href="{{ url('ride/create') }}">{{ __('Book a ride') }}</a></p>
                @endif
            </section>

            <section class="bottom-section">
                <p><h3>{{ __('Recent Activity') }}</h3></p>
                @if(Auth::user()->rideRequests->count() > 0)
                    <div class="activity-list">
                        @foreach(Auth::user()->rideRequests->take(5) as $request)
                            <div class="activity-card">
                                <p>{{ __('Requested ride on') }} {{ $request->created_at->format('M d, Y') }}</p>
                                <p>{{ __('Status:') }} <span class="status-badge">{{ $request->status }}</span></p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>{{ __('No activity yet.') }}</p>
                @endif
            </section>

        </main>
    </div>
@endsection

@push('styles')
    <style>
        .rides-list, .activity-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .ride-card, .activity-card {
            padding: 15px;
            border: 1px solid #bfe0ff;
            border-radius: 8px;
            background: #f4f8ff;
        }
        .ride-card h4 {
            margin: 0 0 10px 0;
            color: #007bff;
        }
        .ride-card p, .activity-card p {
            margin: 5px 0;
            color: #555;
        }
        .btn-secondary {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        .btn-secondary:hover {
            background-color: #0056b3;
        }
        .status-badge {
            padding: 4px 8px;
            background-color: #e8f4f8;
            color: #007bff;
            border-radius: 3px;
            font-size: 0.85rem;
        }
    </style>
@endpush
