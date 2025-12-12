@extends('layouts.app')

@push('head')
    @vite(['resources/css/driver-dashboard.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                    <a href="{{ route('user.view', Auth::user()) }}" class="driver-nav-link">
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
                    <a href="{{ route('user.view', Auth::user()) }}" class="driver-nav-link">
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
                    <a href="{{ route('user.view', Auth::user()) }}" class="driver-nav-link">
                        <i class="fa-solid fa-user-gear"></i> Profile
                    </a>
                </nav>
            @endif
        @endauth
    </aside>

    <main class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-cog"></i> Account Settings</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-user-circle"></i> Profile Settings</h2>
        </div>
        <div class="card-body">
            <a href="/user/{{Auth::user()->id}}" class="btn btn-secondary" style="margin-right: 10px;">
                <i class="fas fa-arrow-left"></i> Back to Profile
            </a>
            <a href="/user/{{Auth::user()->id}}/profile-pictures" class="btn btn-primary" style="margin-right: 10px;">
                <i class="fas fa-camera"></i> Manage Profile Picture
            </a>
            <a href="/user/{{Auth::user()->id}}/edit" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Account
            </a>

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 15px; margin: 20px 0;">
                    <p style="margin: 0 0 10px 0; color: #856404;">
                        <i class="fas fa-exclamation-triangle"></i> {{ __('credentials.email_unverified_msg') }}
                    </p>
                    <button type="button" class="btn btn-primary" onclick="window.location.href='{{route('verification.notice')}}'">
                        <i class="fas fa-envelope"></i> {{ __('credentials.verify_email') }}
                    </button>
                </div>
            @endif
        </div>
    </div>

    @if ($driverAccount != null)
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-car"></i> Driver Account Information</h2>
            </div>
            <div class="card-body">
                <div style="display: grid; gap: 15px;">
                    <div>
                        <strong><i class="fas fa-id-card"></i> Driver Account Name:</strong> {{$driverAccount->driver_account_name}}
                    </div>
                    <div>
                        <strong><i class="fas fa-user-tie"></i> Driver Type:</strong> {{__('driver_type.' . $driverAccount->driver_type)}}
                    </div>
                    <div>
                        <strong><i class="fas fa-building"></i> Company:</strong> {{$driverAccount->company}}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-tools"></i> Account Actions</h2>
        </div>
        <div class="card-body">
            <a href="/user/{{Auth::user()->id}}/delete" class="btn btn-danger" style="margin-bottom: 15px;">
                <i class="fas fa-trash"></i> Delete Account
            </a>
            <br>
            @if ($driverAccount == null)
                <a href="/driver/enroll" class="btn btn-primary">
                    <i class="fas fa-car-side"></i> Enroll to Driving Program
                </a>
            @else
                <a href="/driver/{{$driverAccount->id}}/edit" class="btn btn-primary" style="margin-right: 10px;">
                    <i class="fas fa-edit"></i> Edit Driver Account
                </a>
                <form action="/driver/{{$driverAccount->id}}/leave" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Leave Driving Program
                    </button>
                </form>
            @endif
        </div>
    </div>
    </main>
</div>
@endsection