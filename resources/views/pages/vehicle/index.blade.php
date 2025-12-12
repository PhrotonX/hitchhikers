@extends('layouts.app')

@push('head')
    <style>
        .vehicle-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 2rem;
            color: var(--text-dark, #333);
        }

        .btn-primary {
            padding: 0.75rem 1.5rem;
            background: var(--primary, #007bff);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--primary-dark, #0056b3);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .vehicles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .vehicle-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .vehicle-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .vehicle-badge {
            display: inline-block;
            background: var(--primary, #007bff);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .vehicle-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-dark, #333);
            margin-bottom: 0.5rem;
        }

        .vehicle-info {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .vehicle-info i {
            color: var(--primary, #007bff);
            width: 20px;
        }

        .vehicle-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .btn-small {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
            text-align: center;
        }

        .btn-edit {
            background: #28a745;
            color: white;
        }

        .btn-edit:hover {
            background: #218838;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .empty-state i {
            font-size: 3rem;
            color: #ccc;
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: #666;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .vehicles-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="vehicle-container">
        <div class="page-header">
            <h1>{{ __('My Vehicles') }}</h1>
            <button class="btn-primary" onclick="window.location.href='{{ route('vehicle.create') }}'">{{ __('Add Vehicle') }}</button>
        </div>

        @if($vehicles && count($vehicles) > 0)
            <div class="vehicles-grid">
                @foreach($vehicles as $vehicle)
                    <div class="vehicle-card">
                        <span class="vehicle-badge">{{ ucfirst($vehicle->type ?? 'Vehicle') }}</span>
                        <div class="vehicle-title">{{ $vehicle->make }} {{ $vehicle->model }}</div>
                        
                        <div class="vehicle-info">
                            <i class="fas fa-hashtag"></i>
                            <span>{{ $vehicle->license_plate }}</span>
                        </div>

                        <div class="vehicle-info">
                            <i class="fas fa-calendar"></i>
                            <span>{{ $vehicle->year }}</span>
                        </div>

                        <div class="vehicle-info">
                            <i class="fas fa-users"></i>
                            <span>{{ $vehicle->capacity }} {{ __('seats') }}</span>
                        </div>

                        <div class="vehicle-info">
                            <i class="fas fa-circle"></i>
                            <span style="color: {{ $vehicle->is_active ? '#28a745' : '#dc3545' }};">
                                {{ $vehicle->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </div>

                        <div class="vehicle-actions">
                            <button class="btn-small btn-edit" onclick="window.location.href='{{ route('vehicle.edit', $vehicle->id) }}'">{{ __('Edit') }}</button>
                            <button class="btn-small btn-delete" onclick="deleteVehicle({{ $vehicle->id }})">{{ __('Delete') }}</button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-car"></i>
                <p>{{ __('No vehicles added yet') }}</p>
                <p style="font-size: 0.95rem; color: #999;">{{ __('Add your first vehicle to start offering rides') }}</p>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function deleteVehicle(id) {
            if (confirm('{{ __("Are you sure you want to delete this vehicle?") }}')) {
                console.log('Deleting vehicle', id);
            }
        }
    </script>
@endpush
