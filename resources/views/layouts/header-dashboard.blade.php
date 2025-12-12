<!--HEADER FOR DRIVER/OWNER DASHBOARDS (Minimal)-->
<header class="toolbar">
    <div class="container toolbar-content">
        <!--LOGO-->
        <a href="/" class="logo">
            <img src="{{ Vite::asset('resources/img/Hitchhike Logo.png') }}" alt="Hitchhike Logo" class="logo-img">
            <span class="logo-text">Hitchhike</span>
        </a>

        <!--MINIMAL PLACEHOLDER (no search bar for dashboards)-->
        <div class="search-bar" style="visibility: hidden;">
            <input type="text" class="search-input" placeholder="Dashboard">
        </div>

        <!--EMPTY NAV LINKS-->
        <div class="nav-links"></div>
        
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
                
                    <!--SETTINGS & LOGOUT-->
                    <div class="sidebar-section">
                        <a href="#" class="sidebar-menu-links">Settings</a>
                        <div class="logout-icon">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <a href="/logout" class="logout-link">Logout</a>
                        </div>
                    </div>
                </div>
            @endauth
        </div> 
    </div>
</header>

<script>
// Sidebar and notification toggle
document.addEventListener('DOMContentLoaded', function() {
    const userIcon = document.getElementById('user-icon');
    const sidebarMenu = document.getElementById('sidebar-menu');
    const notifIcon = document.getElementById('notif-icon');
    const notifList = document.getElementById('notif-list');
    const themeToggle = document.getElementById('theme-toggle');

    if (userIcon && sidebarMenu) {
        userIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebarMenu.classList.toggle('active');
            if (notifList) notifList.classList.remove('active');
        });
    }

    if (notifIcon && notifList) {
        notifIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            notifList.classList.toggle('active');
            if (sidebarMenu) sidebarMenu.classList.remove('active');
        });
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            const icon = themeToggle.querySelector('i');
            if (document.body.classList.contains('dark-mode')) {
                icon.classList.remove('fa-toggle-off');
                icon.classList.add('fa-toggle-on');
            } else {
                icon.classList.remove('fa-toggle-on');
                icon.classList.add('fa-toggle-off');
            }
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (sidebarMenu && !userIcon.contains(e.target) && !sidebarMenu.contains(e.target)) {
            sidebarMenu.classList.remove('active');
        }
        if (notifList && !notifIcon.contains(e.target) && !notifList.contains(e.target)) {
            notifList.classList.remove('active');
        }
    });
});
</script>
