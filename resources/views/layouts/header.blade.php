<header class="main-header">
    <div class="header-logo">
        <img src="{{Vite::asset("resources/img/Hitchhike Logo.png")}}" alt="Logo">
        <span>Hitchhike</span>
    </div>

    <nav class="header-nav">
        <span class="separator">|</span> 
        <a href="/">Home</a>
        <a href="/about">About</a>
        @auth
            <a href="/logout">Logout</a>
        @else
            <a href="/login">Login</a>
        @endauth
    </nav>

    <!-- <a href="/register" class="header-btn">Get Started</a> -->
</header>
