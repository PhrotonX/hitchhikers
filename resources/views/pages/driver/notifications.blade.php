@extends('layouts.app')

@push('head')
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .driver-nav {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin: -10px;
        }
        .driver-nav-link {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 20px;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            color: var(--text-light);
            transition: all 0.2s ease-in-out;
        }
        .driver-nav-link i { width: 20px; }
        .driver-nav-link:hover {
            background-color: var(--background-hover);
            color: var(--primary);
        }
        .driver-nav-link.active {
            background-color: var(--primary-light);
            color: white;
            border-right: 4px solid var(--accent);
        }
        body.dark-mode .driver-nav-link:hover { color: var(--accent); }
        body.dark-mode .driver-nav-link.active { background-color: var(--primary); }
        .notification-page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid var(--border);
            padding-bottom: 20px;
        }
        .notification-page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }
        body.dark-mode .notification-page-header h1 {
            color: white;
        }
        .btn-secondary {
            padding: 10px 20px;
            font-size: 0.9rem;
            font-weight: 600;
            border: 1px solid var(--border);
            border-radius: 8px;
            cursor: pointer;
            background: var(--card-bg);
            color: var(--primary);
            transition: all 0.2s;
        }
        .btn-secondary:hover {
            background-color: var(--background-hover);
            border-color: var(--primary);
        }
        body.dark-mode .btn-secondary {
            background: #334155;
            color: var(--text-light);
            border-color: #4b5563;
        }
        body.dark-mode .btn-secondary:hover {
            background: #4b5563;
            color: white;
        }
        .notification-list-container {
            max-width: 900px;
            margin: 0 auto;
        }
        .notification-list-container h2 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-light);
            margin-top: 30px;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            padding: 20px;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            margin-bottom: 15px;
            transition: box-shadow 0.2s;
        }
        .notification-item:hover {
            box-shadow: var(--shadow);
        }
        .notification-item.unread {
            background: #f4f8ff;
            border-left: 5px solid var(--primary);
        }
        body.dark-mode .notification-item {
            background: #1a243d;
            border-color: #334155;
        }
        body.dark-mode .notification-item.unread {
            background: #334155;
            border-left-color: var(--accent);
        }
        .notification-icon {
            font-size: 1.5rem;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .notification-icon.ride-alert {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .notification-icon.doc-alert {
            background-color: #fef3c7;
            color: #92400e;
        }
        .notification-icon.system-alert {
            background-color: #d1fae5;
            color: #065f46;
        }
        .notification-content {
            flex: 1;
        }
        .notification-content p {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--text-dark);
            line-height: 1.5;
        }
        body.dark-mode .notification-content p {
            color: white;
        }
        .notification-content span {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-top: 5px;
            display: block;
        }
    </style>
@endpush

@section('content')
    <header class="toolbar">
        <div class="container toolbar-content">
            <a href="/" class="logo">
                <img src="{{ Vite::asset('resources/img/Hitchhike Logo.png') }}" alt="Hitchhike Logo" class="logo-img">
                <span class="logo-text">Hitchhike</span>
            </a>

            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Where are you going?">
                <button class="search-button">Find Rides</button>
            </div>

            <div class="nav-links">
                <a href="/dashboard">Home</a>
                <a href="#">My Trips</a>
                <a href="#">Help</a>
            </div>

            <div class="user-actions">
                <button class="theme-toggle" id="theme-toggle">
                    <i class="fa-solid fa-toggle-off"></i>
                </button>

                <div class="notif">
                    <div class="notif-icon" id="notif-icon">
                        <i class="fa-regular fa-bell"></i>
                        <span class="notif-badge" id="notif-badge">3</span>
                    </div>
                </div>

                <div class="notif-list" id="notif-list">
                    <label class="notif-label">Notifications</label>
                    <ul>
                        <li><a href="#">New Message</a></li>
                        <li><a href="#">Reminder:</a></li>
                        <li><a href="#">Trip Accepted</a></li>
                    </ul>
                    <div class="view-all">
                        <a href="#" class="viewAll-link">View All</a>
                    </div>
                </div>

                <div class="user-icon" id="user-icon">
                    <i class="fa-solid fa-user"></i>
                </div>

                <div class="sidebar" id="sidebar-menu">
                    <div class="sidebar-top">
                        <div class="profile">
                            <h3 id="user-name">{{ Auth::user()->name }}</h3>
                            <a href="/profile" class="edit-profile">Edit profile  >  </a>
                        </div>
                    </div>

                    <div class="sidebar-section">
                        <a href="/driver/dashboard" class="sidebar-menu-links">Dashboard</a>
                        <a href="#" class="sidebar-menu-links">Messages</a>
                        <a href="#" class="sidebar-menu-links">History</a>
                    </div>

                    <div class="sidebar-section">
                        <a href="#" class="sidebar-menu-links">Settings</a>
                        <div class="logout-icon">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <a href="{{ route('logout') }}" class="logout-link"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="main-layout">
        <aside class="mlay-side">
            <nav class="driver-nav">
                <a href="/driver/dashboard" class="driver-nav-link">
                    <i class="fa-solid fa-gauge"></i> Dashboard
                </a>
                <a href="/driver/rides" class="driver-nav-link">
                    <i class="fa-solid fa-car"></i> Ride Management
                </a>
                <a href="/driver/earnings" class="driver-nav-link">
                    <i class="fa-solid fa-wallet"></i> Earnings
                </a>
                <a href="/driver/notifications" class="driver-nav-link active">
                    <i class="fa-solid fa-bell"></i> Notifications
                </a>
                <a href="/driver/profile" class="driver-nav-link">
                    <i class="fa-solid fa-user"></i> Profile
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="notification-page-header">
                <h1>Notifications</h1>
                <button class="btn-secondary">Mark all as read</button>
            </div>

            <div class="notification-list-container">
                <h2>New</h2>

                <div class="notification-item unread">
                    <span class="notification-icon ride-alert"><i class="fa-solid fa-car"></i></span>
                    <div class="notification-content">
                        <p>New Ride Request! A passenger is waiting for a ride near SM City Pampanga.</p>
                        <span>Just now</span>
                    </div>
                </div>

                <div class="notification-item unread">
                    <span class="notification-icon doc-alert"><i class="fa-solid fa-triangle-exclamation"></i></span>
                    <div class="notification-content">
                        <p>Your Driver's License expires in 7 days. Please upload a new one to avoid account suspension.</p>
                        <span>1 hour ago</span>
                    </div>
                </div>
                
                <div class="notification-item unread">
                    <span class="notification-icon system-alert"><i class="fa-solid fa-bullhorn"></i></span>
                    <div class="notification-content">
                        <p>Weekly Earnings Summary is ready. You earned ₱4,250 this week!</p>
                        <span>3 hours ago</span>
                    </div>
                </div>

                <h2>Earlier</h2>

                <div class="notification-item">
                    <span class="notification-icon ride-alert"><i class="fa-solid fa-ban"></i></span>
                    <div class="notification-content">
                        <p>Passenger Josef P. has cancelled the ride.</p>
                        <span>1 day ago</span>
                    </div>
                </div>

                <div class="notification-item">
                    <span class="notification-icon doc-alert"><i class="fa-solid fa-check"></i></span>
                    <div class="notification-content">
                        <p>Your OR/CR document has been approved.</p>
                        <span>2 days ago</span>
                    </div>
                </div>
                
                <div class="notification-item">
                    <span class="notification-icon system-alert"><i class="fa-solid fa-dollar-sign"></i></span>
                    <div class="notification-content">
                        <p>A payout of ₱1,200.00 has been sent to your bank account.</p>
                        <span>3 days ago</span>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Hitchhike</h3>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Product</h3>
                    <ul class="footer-links">
                        <li><a href="#">Book a Ride</a></li>
                        <li><a href="#">Offer a Ride</a></li>
                        <li><a href="#">How it works</a></li>
                        <li><a href="#">Safety</a></li>
                        <li><a href="#">Pricing</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Support</h3>
                    <ul class="footer-links">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Community Guidelines</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Stay Updated</h3>
                    <p>Follow us on social media</p>
                    <div class="social-list">
                        <a href="#" class="social-link"><i class="fa-brands fa-facebook-f"></i>Facebook</a>
                        <a href="#" class="social-link"><i class="fa-brands fa-instagram"></i>Instagram</a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2025 Hitchhike. | <a href="#">Privacy Policy</a> | <a href="#">Terms and Conditions</a></p>
            </div>
        </div>
    </footer>
@endsection

@push('scripts')
    @vite(['resources/js/dashboard.js'])
@endpush
