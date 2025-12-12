@extends('layouts.app')

@push('head')
    <style>
        .addresses-container {
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

        .addresses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .address-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .address-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .address-type {
            display: inline-block;
            background: var(--primary, #007bff);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .address-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark, #333);
            margin-bottom: 0.5rem;
        }

        .address-text {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .address-actions {
            display: flex;
            gap: 0.75rem;
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

        .address-form {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark, #333);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary, #007bff);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
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

            .addresses-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="addresses-container">
        <div class="page-header">
            <h1>{{ __('Saved Addresses') }}</h1>
            <button class="btn-primary" onclick="showAddressForm()">{{ __('Add Address') }}</button>
        </div>

        @if($addresses && count($addresses) > 0)
            <div class="addresses-grid">
                @foreach($addresses as $address)
                    <div class="address-card">
                        <span class="address-type">{{ ucfirst($address->type ?? 'Other') }}</span>
                        <div class="address-title">{{ $address->label ?? 'Address' }}</div>
                        <div class="address-text">
                            {{ $address->street }}<br>
                            {{ $address->city }}, {{ $address->state }} {{ $address->zip }}<br>
                            {{ $address->country }}
                        </div>
                        <div class="address-actions">
                            <button class="btn-small btn-edit" onclick="editAddress({{ $address->id }})">{{ __('Edit') }}</button>
                            <button class="btn-small btn-delete" onclick="deleteAddress({{ $address->id }})">{{ __('Delete') }}</button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-map-marker-alt"></i>
                <p>{{ __('No saved addresses yet') }}</p>
                <p style="font-size: 0.95rem; color: #999;">{{ __('Add your first address to get started') }}</p>
            </div>
        @endif

        <div class="address-form" id="addressForm" style="display: none;">
            <h2>{{ __('Add New Address') }}</h2>
            <form method="POST" action="{{ route('addresses.store') ?? '#' }}">
                @csrf
                
                <div class="form-group">
                    <label for="addressType">{{ __('Address Type') }}</label>
                    <select id="addressType" name="type" class="form-control" required>
                        <option value="home">{{ __('Home') }}</option>
                        <option value="work">{{ __('Work') }}</option>
                        <option value="other">{{ __('Other') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="addressLabel">{{ __('Label') }}</label>
                    <input type="text" id="addressLabel" name="label" class="form-control" placeholder="{{ __('e.g., My Home') }}" required>
                </div>

                <div class="form-group">
                    <label for="street">{{ __('Street Address') }}</label>
                    <input type="text" id="street" name="street" class="form-control" placeholder="{{ __('123 Main St') }}" required>
                </div>

                <div class="form-group">
                    <label for="city">{{ __('City') }}</label>
                    <input type="text" id="city" name="city" class="form-control" placeholder="{{ __('City') }}" required>
                </div>

                <div class="form-group">
                    <label for="state">{{ __('State/Province') }}</label>
                    <input type="text" id="state" name="state" class="form-control" placeholder="{{ __('State') }}" required>
                </div>

                <div class="form-group">
                    <label for="zip">{{ __('ZIP/Postal Code') }}</label>
                    <input type="text" id="zip" name="zip" class="form-control" placeholder="{{ __('12345') }}" required>
                </div>

                <div class="form-group">
                    <label for="country">{{ __('Country') }}</label>
                    <input type="text" id="country" name="country" class="form-control" placeholder="{{ __('Country') }}" required>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn-primary" style="flex: 1;">{{ __('Save Address') }}</button>
                    <button type="button" class="btn-primary" style="background: #6c757d; flex: 1;" onclick="hideAddressForm()">{{ __('Cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showAddressForm() {
            document.getElementById('addressForm').style.display = 'block';
            document.getElementById('addressForm').scrollIntoView({ behavior: 'smooth' });
        }

        function hideAddressForm() {
            document.getElementById('addressForm').style.display = 'none';
        }

        function editAddress(id) {
            console.log('Editing address', id);
            // Show form and populate with address data
        }

        function deleteAddress(id) {
            if (confirm('{{ __("Are you sure you want to delete this address?") }}')) {
                console.log('Deleting address', id);
            }
        }
    </script>
@endpush
