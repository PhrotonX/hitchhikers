@extends('layouts.app')

@push('head')
    @vite(['resources/css/driver-dashboard.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
<div class="main-layout">
    <x-sidebar-nav />

    <main class="main-content">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">{{$user->getFullName()}}</h2>
            </div>
            <div class="card-body">
                <p><strong>Date joined: </strong>{{$user->created_at->format('F d, Y')}}</p>

                @auth
                    @if ($user->id == Auth::user()->id)
                        <div style="margin-top: 20px;">
                            <a href="/settings" class="btn btn-primary">Settings</a>
                            <a href="/user/{{$user->id}}/profile-pictures" class="btn btn-secondary">Manage Profile Pictures</a>
                        </div>
                    @endif
                @endauth

                @if ($user->isDriver())
                    <div class="card" style="margin-top: 20px;">
                        <div class="card-header">
                            @auth
                                <h3 class="card-title">Your Vehicles</h3>
                            @else
                                <h3 class="card-title">Vehicles</h3>
                            @endauth
                        </div>
                        <div class="card-body">
                            @forelse ($vehicleDrivers ?? [] as $vehicleDriver)
                                <div class="vehicle-item" style="padding: 15px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <a href="/vehicle/{{$vehicleDriver->vehicle->id}}" style="font-weight: 600; font-size: 1.1rem; color: var(--primary);">{{$vehicleDriver->vehicle->vehicle_name}}</a>
                                    </div>
                                    @auth
                                        @if ($user->id == Auth::user()->id)
                                            <div style="display: flex; gap: 10px;">
                                                <a href="/vehicle/{{$vehicleDriver->vehicle->id}}/edit" class="btn btn-sm btn-secondary">{{__('string.edit')}}</a>
                                                <form action="/vehicle/{{$vehicleDriver->vehicle->id}}/delete" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this vehicle?')">{{__('string.delete')}}</button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            @empty
                                <p style="color: var(--text-light);">No vehicles registered yet.</p>
                            @endforelse
                            
                            @auth
                                @if ($user->id == Auth::user()->id && $user->isDriver())
                                    <div style="margin-top: 20px;">
                                        <a href="/vehicle/create" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Create a vehicle</a>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                @endif
                @if (isset($status))
                    <div class="alert alert-success" style="margin-top: 20px; padding: 15px; background: var(--secondary); color: white; border-radius: 8px;">
                        {{$status}}
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>
@endsection