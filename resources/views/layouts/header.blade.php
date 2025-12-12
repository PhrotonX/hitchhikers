<!--HEADER / TOOLBAR-->
<header class="toolbar">
    <div class="container toolbar-content">
        <!--LOGO-->
        <a href="/" class="logo">
            <img src="{{ Vite::asset('resources/img/Hitchhike Logo.png') }}" alt="Hitchhike Logo" class="logo-img">
            <span class="logo-text">Hitchhike</span>
        </a>

        <!--SEARCH BAR-->
        <div class="search-bar">
            <input type="text" class="search-input" placeholder="Where are you going?">
            <button class="search-button">Find Rides</button>
        </div>

        <!--NAVIGATION LINKS-->
        <div class="nav-links">
            <a href="/">Home</a>
            @auth
                @if (Auth::user()->isPrivileged('owner'))
                    <a href="{{ route('owner.dashboard') }}">Dashboard</a>
                @elseif (Auth::user()->isDriver())
                    <a href="{{ route('driver.dashboard') }}">My Rides</a>
                @else
                    <a href="/ride/requests/created">My Trips</a>
                @endif
            @else
                <a href="/about">About</a>
            @endauth
            <a href="#">Help</a>
        </div>

        <div class="user-actions">
            <!--THEME TOGGLE-->
            <button class="theme-toggle" id="theme-toggle">
                <i class="fa-solid fa-toggle-off"></i>
            </button>

            @auth
                <!--NOTIFICATION-->
                <div class="notif">
                    <div class="notif-icon" id="notif-icon">
                        <i class="fa-regular fa-bell"></i>
                        <span class="notif-badge" id="notif-badge">0</span>
                    </div>
                </div>

                <div class="notif-list" id="notif-list">
                    <label class="notif-label">Notifications</label>
                    <ul>
                        <li><a href="#">No new notifications</a></li>
                    </ul>
                    <div class="view-all">
                        <a href="#" class="viewAll-link">View All</a>
                    </div>
                </div>
                
                <!--USER ICON-->
                <div class="user-icon" id="user-icon">
                    <i class="fa-solid fa-user"></i>
                </div>

                <!--SIDEBAR DROPDOWN MENU-->
                <div class="sidebar" id="sidebar-menu">
                    <!--PROFILE SECTION-->
                    <div class="sidebar-top">
                        <div class="profile">
                            <h3 id="user-name">{{ Auth::user()->getFullName() }}</h3>
                            <a href="{{ route('user.view', Auth::user()) }}" class="edit-profile">Edit profile  >  </a>        
                        </div>
                    </div>
                
                    <!--DASHBOARD/MESSAGES/HISTORY SECTION-->
                    <div class="sidebar-section">
                        @if (Auth::user()->isPrivileged('owner'))
                            <a href="{{ route('owner.dashboard') }}" class="sidebar-menu-links">Owner Dashboard</a>
                        @elseif (Auth::user()->isDriver())
                            <a href="{{ route('driver.dashboard') }}" class="sidebar-menu-links">Driver Dashboard</a>
                        @else
                            <a href="/" class="sidebar-menu-links">Dashboard</a>
                        @endif
                        <a href="#" class="sidebar-menu-links">Messages</a> 
                        <a href="/ride/requests/created" class="sidebar-menu-links">History</a>       
                    </div>

                    <!--SETTINGS & LOGOUT-->
                    <div class="sidebar-section">
                        <a href="#" class="sidebar-menu-links">Settings</a>
                        <div class="logout-icon">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <a href="/logout" class="logout-link">Logout</a>
                        </div>
                    </div>
                </div>
            @else
                <!--GUEST USER - NO SIDEBAR-->
            @endauth
        </div> 
    </div>
</header>