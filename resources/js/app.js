import.meta.glob([ '../img/**', ]);

// Toolbar interactive elements - Sidebar menu, notifications, theme toggle
document.addEventListener('DOMContentLoaded', function() {
    // User sidebar menu toggle
    const userIcon = document.getElementById('user-icon');
    const sidebarMenu = document.getElementById('sidebar-menu');
    
    if (userIcon && sidebarMenu) {
        userIcon.addEventListener('click', () => {
            sidebarMenu.classList.toggle('active');
        });
        
        document.addEventListener('click', (e) => {
            if (!userIcon.contains(e.target) && !sidebarMenu.contains(e.target)) {
                sidebarMenu.classList.remove('active');
            }
        });
    }

    // Notification dropdown toggle
    const notifIcon = document.getElementById('notif-icon');
    const notifList = document.getElementById('notif-list');
    
    if (notifIcon && notifList) {
        notifIcon.addEventListener('click', () => {
            notifList.classList.toggle('active');
        });
        
        document.addEventListener('click', (e) => {
            if (!notifIcon.contains(e.target) && !notifList.contains(e.target)) {
                notifList.classList.remove('active');
            }
        });
    }

    // Theme toggle
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
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
});