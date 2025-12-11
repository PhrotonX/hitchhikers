<header class="main-header">
    <div class="header-logo">
        <img src="{{Vite::asset("resources/img/Hitchhike Logo.png")}}" alt="Logo"> <!-- Hitch Hike Logo -->
        <span>Hitchhike</span>
    </div>

    <nav class="header-nav">
        <span class="separator">|</span> 
        <a href="/">Home</a> <!-- Route to Dashboard -->
        <a href="/about">About</a> <!-- Route to About -->
        @auth
            <a href="/logout">Logout</a> <!-- Route to Log In -->
        @else
            <a href="/login">Login</a> <!-- Route to Log In -->
        @endauth
        
        @auth
            @if (!Auth::user()->isDriver())
                <a href="/ride/requests/created">My Ride Requests</a>
            @endif
        @endauth
    </nav>

    <a href="/register" class="header-btn">Get Started</a> <!-- Route to SignUp -->
</header>