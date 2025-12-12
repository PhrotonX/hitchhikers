@extends('layouts.app')

@section('content')
<x-sidebar-nav />
<div class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-car"></i> {{$vehicle->vehicle_name}}</h1>
        <div style="margin-top: 10px;">
            <a href="/vehicle/{{$vehicle->id}}/edit" class="btn btn-primary" style="margin-right: 10px;">
                <i class="fas fa-edit"></i> Edit Vehicle
            </a>
            <form action="/vehicle/{{$vehicle->id}}/delete" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this vehicle?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete Vehicle
                </button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-info-circle"></i> Vehicle Details</h2>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div>
                    <strong><i class="fas fa-id-card"></i> Plate Number:</strong>
                    <p style="margin: 5px 0 0 0; font-size: 16px;">{{$vehicle->plate_number ?? 'N/A'}}</p>
                </div>
                <div>
                    <strong><i class="fas fa-car"></i> Vehicle Name:</strong>
                    <p style="margin: 5px 0 0 0; font-size: 16px;">{{$vehicle->vehicle_name}}</p>
                </div>
                <div>
                    <strong><i class="fas fa-industry"></i> Brand:</strong>
                    <p style="margin: 5px 0 0 0; font-size: 16px;">{{$vehicle->vehicle_brand}}</p>
                </div>
                <div>
                    <strong><i class="fas fa-tag"></i> Model:</strong>
                    <p style="margin: 5px 0 0 0; font-size: 16px;">{{$vehicle->vehicle_model ?? 'N/A'}}</p>
                </div>
                <div>
                    <strong><i class="fas fa-palette"></i> Color:</strong>
                    <p style="margin: 5px 0 0 0; font-size: 16px;">{{$vehicle->color}}</p>
                </div>
                <div>
                    <strong><i class="fas fa-users"></i> Maximum Capacity:</strong>
                    <p style="margin: 5px 0 0 0; font-size: 16px;">{{$vehicle->capacity ?? 'N/A'}} passengers</p>
                </div>
                <div>
                    <strong><i class="fas fa-car-side"></i> Type:</strong>
                    <p style="margin: 5px 0 0 0; font-size: 16px;">{{$vehicle->type ?? 'N/A'}}</p>
                </div>
                <div>
                    <strong><i class="fas fa-circle-info"></i> Status:</strong>
                    <p style="margin: 5px 0 0 0; font-size: 16px;">
                        @if($vehicle->status == 'active')
                            <span style="color: #28a745;">
                                <i class="fas fa-check-circle"></i> Active
                            </span>
                        @else
                            <span style="color: #dc3545;">
                                <i class="fas fa-times-circle"></i> {{ucfirst($vehicle->status ?? 'Unknown')}}
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if($vehicle->latitude && $vehicle->longitude)
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-map-marker-alt"></i> Location</h2>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                    <div>
                        <strong><i class="fas fa-location-crosshairs"></i> Latitude:</strong>
                        <p style="margin: 5px 0 0 0; font-size: 16px;">{{$vehicle->latitude}}</p>
                    </div>
                    <div>
                        <strong><i class="fas fa-location-crosshairs"></i> Longitude:</strong>
                        <p style="margin: 5px 0 0 0; font-size: 16px;">{{$vehicle->longitude}}</p>
                    </div>
                </div>
                <div id="map" style="height: 300px; border-radius: 8px; border: 1px solid #ddd;"></div>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-clock"></i> Timestamps</h2>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <strong><i class="fas fa-calendar-plus"></i> Created At:</strong>
                    <p style="margin: 5px 0 0 0; font-size: 16px;">{{$vehicle->created_at->format('F d, Y h:i A')}}</p>
                </div>
                <div>
                    <strong><i class="fas fa-calendar-check"></i> Last Updated:</strong>
                    <p style="margin: 5px 0 0 0; font-size: 16px;">{{$vehicle->updated_at->format('F d, Y h:i A')}}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@if($vehicle->latitude && $vehicle->longitude)
@push('scripts')
<script>
    const map = L.map('map').setView([{{$vehicle->latitude}}, {{$vehicle->longitude}}], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    L.marker([{{$vehicle->latitude}}, {{$vehicle->longitude}}])
        .addTo(map)
        .bindPopup('{{$vehicle->vehicle_name}}')
        .openPopup();
</script>
@endpush
@endif
@endsection