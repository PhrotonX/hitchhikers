<style>
    .main-header {
    width: 99vw;
    padding: 12px 40px;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    gap: 35px;
    box-shadow: 0 2px 6px rgb(0 0 0 / 10%);
    position: fixed;
    top: 0;
    left: 0;
    z-index: 100;
}

.header-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 20px;
    font-weight: 600;
    color: #333;
}

.header-logo img {
    width: 50px;
    height: 50px;
}

.header-nav {
    display: flex;
    gap: 35px;
    justify-content: flex-start;
}

.header-nav a {
    text-decoration: none;
    color: #333;
    font-weight: 500;
    transition: 0.3s;
}

.header-nav a:hover {
    color: #0066ff;
}


.separator {
    font-size: 18px;
    color: #333;
    margin-right: 10px;
}


.header-btn {
    margin-left: auto;
    margin-right: 2vw;
    padding: 8px 16px;
    border-radius: 8px;
    background: var(--primary);
    color: white;
    border: 2px solid var(--primary); /* ensure button has border */
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
}

.header-btn:hover {
    background: transparent;
    color: var(--primary);          /* make text black when hovered */
    border-color: var(--primary);   /* keep border black */
    transform: translateY(-2px);
}
</style>

<header class="main-header">
    <div class="header-logo">
        <img src="{{Vite::asset("resources/img/Hitchhike Logo.png")}}" alt="Logo"> <!-- Hitch Hike Logo -->
        <span>Hitchhike</span>
    </div>

    <nav class="header-nav">
        <span class="separator">|</span> 
        <a href="/">Home</a> <!-- Route to Dashboard -->
        <a href="/about">About</a> <!-- Route to About -->
        <a href="/login">Login</a> <!-- Route to Log In -->
        @if (!Auth::user()->isDriver())
            <a href="/ride/requests/created">My Ride Requests</a>
        @endif
    </nav>

    <a href="/register" class="header-btn">Get Started</a> <!-- Route to SignUp -->
</header>