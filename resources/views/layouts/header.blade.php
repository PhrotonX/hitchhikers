<header class="main-header">
    <div class="header-logo">
        <img src="{{Vite::asset("resources/img/Hitchhike Logo.png")}}" alt="Logo">
        <span>Hitchhike</span>
    </div>

    <nav class="header-nav">
        <span class="separator">|</span> 
        <a href="/">Home</a>
        @auth
            @if (Auth::user()->isPrivileged('owner'))
                <a href="{{ route('owner.dashboard') }}">Owner Dashboard</a>
            @elseif (Auth::user()->isDriver())
                <a href="{{ route('driver.dashboard') }}">Driver Dashboard</a>
            @endif
        @endauth
        <a href="/about">About</a>
        @auth
            <a href="/logout">Logout</a>
        @else
            <a href="/login">Login</a>
        @endauth
        
        @auth
            @if (!Auth::user()->isDriver() && !Auth::user()->isPrivileged('owner'))
                <a href="/ride/requests/created">My Ride Requests</a>
            @endif
        @endauth
    </nav>

    <a href="/register" class="header-btn">Get Started</a>
</header>