@props([
    'gender' => [
        'male' => __('gender.male'),
        'female' => __('gender.female'),
    ],
])

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
        <h1><i class="fas fa-exclamation-triangle" style="color: #dc3545;"></i> {{ __('credentials.delete_account') }}</h1>
    </div>

    <div class="card" style="border-left: 4px solid #dc3545;">
        <div class="card-header" style="background: #f8d7da;">
            <h2 class="card-title" style="color: #721c24;"><i class="fas fa-trash"></i> {{ __('credentials.delete_account_confirmation') }}</h2>
        </div>
        <div class="card-body">
            <div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 6px; padding: 15px; margin-bottom: 20px;">
                <p style="margin: 0; color: #856404;">
                    <strong><i class="fas fa-info-circle"></i> {{ __('credentials.delete_account_info') }}</strong>
                </p>
            </div>

            <div style="background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 6px; padding: 15px; margin-bottom: 20px;">
                <p style="margin: 0; color: #721c24;">
                    <strong><i class="fas fa-exclamation-triangle"></i> {{ __('credentials.delete_account_warning') }}</strong>
                </p>
            </div>

            <form action="{{ route('user.destroy', ['user' => Auth::user()->id]) }}" method="POST">
                @csrf
                @method('DELETE')

                <div style="margin-bottom: 20px;">
                    <label><i class="fas fa-lock"></i> {{ __('credentials.password') }} *</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        placeholder="{{ __('credentials.password') }}"
                        required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;"
                    />
                    @error('password')
                        <span style="color: #dc3545; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='/user/{{Auth::user()->id}}'">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Account Permanently
                    </button>
                </div>
            </form>
        </div>
    </div>
    </main>
</div>
@endsection