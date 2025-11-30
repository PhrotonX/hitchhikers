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
        .ride-management-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            align-items: flex-start;
        }
        @media (max-width: 1024px) {
            .ride-management-layout {
                grid-template-columns: 1fr;
            }
        }
        .card {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 24px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }
        body.dark-mode .card {
            background-color: #1a243d;
            border: 1px solid #334155;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }
        body.dark-mode .card-title { color: white; }
        .btn {
            padding: 12px 15px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            width: 100%;
        }
        .btn-primary { background-color: var(--primary); color: white; }
        .btn-primary:hover { background-color: var(--primary-light); }
        .btn-secondary { background-color: var(--border); color: var(--text-dark); }
        .btn-secondary:hover { background-color: #d1d5db; }
        .btn-success { background-color: var(--secondary); color: white; }
        .btn-success:hover { background-color: #059669; }
        .btn-danger { background-color: var(--error); color: white; }
        .btn-danger:hover { background-color: #dc2626; }
        .map-container {
            width: 100%;
            height: 250px;
            background-color: #e0e0e0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-weight: 600;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
        .ride-details-list {
            list-style: none;
            padding: 0;
        }
        .ride-details-list li {
            font-size: 1.1rem;
            color: var(--text-light);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .ride-details-list li i {
            color: var(--primary);
            width: 20px;
            text-align: center;
        }
        .ride-details-list strong {
            color: var(--text-dark);
        }
        body.dark-mode .ride-details-list strong { color: white; }
        .card-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
        }
        .placeholder-card {
            padding: 40px;
            text-align: center;
            color: var(--text-light);
            border: 2px dashed var(--border);
        }
        body.dark-mode .placeholder-card {
            border-color: #334155;
        }
        .ride-request-feed {
            max-height: 800px;
            overflow-y: auto;
            padding-right: 10px;
        }
        .ride-request-card {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: box-shadow 0.2s;
        }
        .ride-request-card:hover { box-shadow: var(--shadow-hover); }
        .ride-passenger {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
        }
        body.dark-mode .ride-passenger { color: white; }
        .ride-locations {
            list-style: none;
            padding: 10px 0 0 35px;
            font-size: 0.9rem;
        }
        .ride-locations li {
            position: relative;
            padding-left: 20px;
            color: var(--text-light);
            margin-bottom: 5px;
        }
        .ride-locations li i {
            position: absolute;
            left: 0;
            top: 4px;
            color: var(--primary);
        }
        .ride-locations li.dropoff i {
            color: var(--secondary);
        }
        .ride-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 15px;
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
                        <span class="notif-badge" id="notif-badge">0</span>
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
                <a href="/driver/rides" class="driver-nav-link active">
                    <i class="fa-solid fa-car"></i> Ride Management
                </a>
                <a href="/driver/earnings" class="driver-nav-link">
                    <i class="fa-solid fa-wallet"></i> Earnings
                </a>
                <a href="/driver/notifications" class="driver-nav-link">
                    <i class="fa-solid fa-bell"></i> Notifications
                </a>
                <a href="/driver/profile" class="driver-nav-link">
                    <i class="fa-solid fa-user"></i> Profile
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="ride-management-layout">
                <div>
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Active Ride</h2>
                        </div>
                        <div class="map-container">
                            Map Placeholder
                        </div>
                        <ul class="ride-details-list">
                            <li><i class="fa-solid fa-location-dot"></i> <strong>Pickup:</strong> Main St & 5th Ave</li>
                            <li><i class="fa-solid fa-location-arrow"></i> <strong>Dropoff:</strong> Airport Terminal 3</li>
                            <li><i class="fa-solid fa-clock"></i> <strong>ETA:</strong> 12 minutes</li>
                            <li><i class="fa-solid fa-user"></i> <strong>Passengers:</strong> 2/4</li>
                        </ul>
                        <div class="card-actions">
                            <button class="btn btn-success">Complete Ride</button>
                            <button class="btn btn-danger">Cancel Ride</button>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Ride Requests</h2>
                        </div>
                        <div class="ride-request-feed">
                            <div class="ride-request-card">
                                <div class="ride-passenger">
                                    <i class="fa-solid fa-user-circle"></i> Jane Doe
                                </div>
                                <ul class="ride-locations">
                                    <li><i class="fa-solid fa-circle"></i> Pickup: Union Square</li>
                                    <li class="dropoff"><i class="fa-solid fa-circle"></i> Dropoff: Downtown</li>
                                </ul>
                                <div class="ride-actions">
                                    <button class="btn btn-success">Accept</button>
                                    <button class="btn btn-secondary">Decline</button>
                                </div>
                            </div>

                            <div class="ride-request-card">
                                <div class="ride-passenger">
                                    <i class="fa-solid fa-user-circle"></i> John Smith
                                </div>
                                <ul class="ride-locations">
                                    <li><i class="fa-solid fa-circle"></i> Pickup: City Center</li>
                                    <li class="dropoff"><i class="fa-solid fa-circle"></i> Dropoff: Stadium</li>
                                </ul>
                                <div class="ride-actions">
                                    <button class="btn btn-success">Accept</button>
                                    <button class="btn btn-secondary">Decline</button>
                                </div>
                            </div>
                        </div>
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
