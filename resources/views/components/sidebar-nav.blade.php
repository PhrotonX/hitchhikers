{{-- Reusable Sidebar Navigation Component --}}
<aside class="mlay-side">
    @auth
        @if (Auth::user()->isPrivileged('owner'))
            {{-- Owner Navigation --}}
            <nav class="driver-nav">
                <a href="{{ route('owner.dashboard') }}" class="driver-nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i> Statistics
                </a>
                <a href="#" class="driver-nav-link">
                    <i class="fa-solid fa-clipboard-list"></i> Audit Logs
                </a>
                <a href="#" class="driver-nav-link">
                    <i class="fa-solid fa-users"></i> Users
                </a>
                <a href="{{ route('user.view', Auth::user()) }}" class="driver-nav-link {{ request()->routeIs('user.view') && request()->route('user')->id == Auth::user()->id ? 'active' : '' }}">
                    <i class="fa-solid fa-user-gear"></i> Profile
                </a>
            </nav>
        @elseif (Auth::user()->isDriver())
            {{-- Driver Navigation --}}
            <nav class="driver-nav">
                <a href="{{ route('driver.dashboard') }}" class="driver-nav-link {{ request()->routeIs('home') || request()->routeIs('driver.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="{{ route('driver.earnings') }}" class="driver-nav-link {{ request()->routeIs('driver.earnings') ? 'active' : '' }}">
                    <i class="fa-solid fa-dollar-sign"></i> Earnings
                </a>
                <a href="{{ route('user.view', Auth::user()) }}" class="driver-nav-link {{ request()->routeIs('user.view') && request()->route('user')->id == Auth::user()->id ? 'active' : '' }}">
                    <i class="fa-solid fa-user-gear"></i> Profile
                </a>
            </nav>
        @else
            {{-- Passenger Navigation --}}
            <nav class="driver-nav">
                <a href="{{ route('home') }}" class="driver-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fa-solid fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="/ride/requests/created" class="driver-nav-link {{ request()->is('ride/requests/created') ? 'active' : '' }}">
                    <i class="fa-solid fa-car"></i> My Ride Requests
                </a>
                <a href="{{ route('user.view', Auth::user()) }}" class="driver-nav-link {{ request()->routeIs('user.view') && request()->route('user')->id == Auth::user()->id ? 'active' : '' }}">
                    <i class="fa-solid fa-user-gear"></i> Profile
                </a>
            </nav>
        @endif
    @endauth
</aside>
