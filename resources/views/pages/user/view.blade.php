@extends('layouts.app')

@push('head')
    @vite(['resources/css/driver-dashboard.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section('content')
<div class="main-layout container">
    <aside class="mlay-side">
        @auth
            @if (Auth::user()->isPrivileged('owner'))
                <nav class="driver-nav">
                    <a href="{{ route('owner.dashboard') }}" class="driver-nav-link">
                        <i class="fa-solid fa-chart-line"></i> Statistics
                    </a>
                    <a href="#" class="driver-nav-link">
                        <i class="fa-solid fa-clipboard-list"></i> Audit Logs
                    </a>
                    <a href="#" class="driver-nav-link">
                        <i class="fa-solid fa-users"></i> Users
                    </a>
                    <a href="{{ route('user.view', Auth::user()) }}" class="driver-nav-link active">
                        <i class="fa-solid fa-user-gear"></i> Profile
                    </a>
                </nav>
            @elseif (Auth::user()->isDriver())
                <nav class="driver-nav">
                    <a href="{{ route('driver.dashboard') }}" class="driver-nav-link">
                        <i class="fa-solid fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="{{ route('driver.earnings') }}" class="driver-nav-link">
                        <i class="fa-solid fa-dollar-sign"></i> Earnings
                    </a>
                    <a href="{{ route('user.view', Auth::user()) }}" class="driver-nav-link active">
                        <i class="fa-solid fa-user-gear"></i> Profile
                    </a>
                </nav>
            @else
                <nav class="driver-nav">
                    <a href="{{ route('home') }}" class="driver-nav-link">
                        <i class="fa-solid fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="/ride/requests/created" class="driver-nav-link">
                        <i class="fa-solid fa-car"></i> My Ride Requests
                    </a>
                    <a href="{{ route('user.view', Auth::user()) }}" class="driver-nav-link active">
                        <i class="fa-solid fa-user-gear"></i> Profile
                    </a>
                </nav>
            @endif
        @endauth
    </aside>

    <main class="main-content">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-user-circle"></i> User Profile</h2>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 30px; align-items: start; margin-bottom: 30px;">
                    @php
                        $profilePicture = $user->getProfilePicture();
                        $profilePicUrl = $profilePicture && $profilePicture->getPath(\App\Models\ProfilePicture::SIZE_LARGE_SUFFIX) 
                            ? asset('storage/' . $profilePicture->getPath(\App\Models\ProfilePicture::SIZE_LARGE_SUFFIX)) 
                            : Vite::asset('resources/img/question_mark.png');
                    @endphp
                    <div style="flex-shrink: 0;">
                        <img src="{{$profilePicUrl}}" alt="{{$user->getFullName()}}" 
                             style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid #e5e7eb;">
                    </div>
                    <div style="flex-grow: 1;">
                        <h2 style="margin: 0 0 10px 0; font-size: 28px; font-weight: 700;">{{$user->getFullName()}}</h2>
                        <p style="margin: 0 0 5px 0; color: #666;"><i class="fas fa-envelope"></i> {{$user->email}}</p>
                        <p style="margin: 0 0 5px 0; color: #666;"><i class="fas fa-phone"></i> {{$user->phone ?? 'Not provided'}}</p>
                        <p style="margin: 0 0 15px 0; color: #666;"><i class="fas fa-calendar-plus"></i> Member since {{$user->created_at->format('F d, Y')}}</p>
                        
                        @auth
                            @if ($user->id == Auth::user()->id)
                                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                    <a href="/settings" class="btn btn-primary"><i class="fas fa-cog"></i> Settings</a>
                                    <a href="/user/{{$user->id}}/profile-pictures" class="btn btn-secondary"><i class="fas fa-camera"></i> Manage Profile Pictures</a>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>

                @if($user->isDriver())
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <h3 style="margin: 0 0 10px 0; color: #1e3a8a;"><i class="fas fa-id-card"></i> Driver Information</h3>
                        <p style="margin: 5px 0;"><strong>Driver Account:</strong> {{$user->driverAccount->driver_account_name ?? 'N/A'}}</p>
                        <p style="margin: 5px 0;"><strong>Driver Type:</strong> {{$user->driverAccount ? __('driver_type.' . $user->driverAccount->driver_type) : 'N/A'}}</p>
                        <p style="margin: 5px 0;"><strong>Company:</strong> {{$user->driverAccount->company ?? 'Independent'}}</p>
                    </div>
                @endif

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