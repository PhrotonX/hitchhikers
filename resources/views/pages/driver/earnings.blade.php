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
        .earnings-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            align-items: flex-start;
        }
        @media (max-width: 1024px) {
            .earnings-layout { grid-template-columns: 1fr; }
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
        }
        .btn-primary { background-color: var(--primary); color: white; }
        .btn-primary:hover { background-color: var(--primary-light); }
        .btn-secondary { background-color: var(--border); color: var(--text-dark); }
        .btn-secondary:hover { background-color: #d1d5db; }
        .earnings-summary-card {
            text-align: center;
        }
        .earnings-label {
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--text-light);
            margin-bottom: 10px;
        }
        .earnings-amount {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0;
        }
        body.dark-mode .earnings-amount {
            color: var(--accent);
        }
        .payout-info {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-top: 15px;
        }
        .earnings-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--border);
        }
        .tab-button {
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            background: none;
            cursor: pointer;
            color: var(--text-light);
            border-bottom: 3px solid transparent;
        }
        .tab-button.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }
        body.dark-mode .tab-button.active {
            color: var(--accent);
            border-bottom-color: var(--accent);
        }
        .transaction-list {
            list-style: none;
            padding: 0;
        }
        .transaction-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 10px;
            border-bottom: 1px solid var(--border);
        }
        .transaction-item:last-child {
            border-bottom: none;
        }
        .transaction-details {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .transaction-icon {
            font-size: 1.5rem;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .transaction-icon.ride {
            background-color: #d1fae5;
            color: #065f46;
        }
        .transaction-icon.payout {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .transaction-info p {
            margin: 0;
            font-weight: 600;
            color: var(--text-dark);
        }
        body.dark-mode .transaction-info p { color: white; }
        .transaction-info span {
            font-size: 0.9rem;
            color: var(--text-light);
        }
        .transaction-amount {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--secondary);
        }
        .transaction-amount.negative {
            color: var(--error);
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
                <a href="/driver/rides" class="driver-nav-link">
                    <i class="fa-solid fa-car"></i> Ride Management
                </a>
                <a href="/driver/earnings" class="driver-nav-link active">
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
            <div class="earnings-layout">
                <div>
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Earnings History</h2>
                        </div>
                        <div class="earnings-tabs">
                            <button class="tab-button active">Today</button>
                            <button class="tab-button">This Week</button>
                            <button class="tab-button">This Month</button>
                        </div>
                        
                        <h3 class="earnings-label" style="text-align: left;">Today's Total: <span class="earnings-amount" style="font-size: 2rem;">₱850.00</span></h3>
                        
                        <ul class="transaction-list">
                            <li class="transaction-item">
                                <div class="transaction-details">
                                    <span class="transaction-icon ride"><i class="fa-solid fa-car"></i></span>
                                    <div class="transaction-info">
                                        <p>Ride Fare</p>
                                        <span>Passenger: Josef P.</span>
                                    </div>
                                </div>
                                <span class="transaction-amount">+ ₱150.00</span>
                            </li>
                            <li class="transaction-item">
                                <div class="transaction-details">
                                    <span class="transaction-icon ride"><i class="fa-solid fa-car"></i></span>
                                    <div class="transaction-info">
                                        <p>Ride Fare</p>
                                        <span>Passenger: Maria S.</span>
                                    </div>
                                </div>
                                <span class="transaction-amount">+ ₱200.00</span>
                            </li>
                            <li class="transaction-item">
                                <div class="transaction-details">
                                    <span class="transaction-icon ride"><i class="fa-solid fa-car"></i></span>
                                    <div class="transaction-info">
                                        <p>Ride Fare</p>
                                        <span>Passenger: John D.</span>
                                    </div>
                                </div>
                                <span class="transaction-amount">+ ₱500.00</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div>
                    <div class="card earnings-summary-card">
                        <div class="card-header">
                            <h2 class="card-title">Current Balance</h2>
                        </div>
                        <p class="earnings-label">Available for Payout</p>
                        <h1 class="earnings-amount">₱4,250.00</h1>
                        <p class="payout-info">Next payout: Every Friday</p>
                        <button class="btn btn-primary" style="margin-top: 20px;">Request Payout</button>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Weekly Summary</h2>
                        </div>
                        <ul class="transaction-list">
                            <li class="transaction-item">
                                <div class="transaction-details">
                                    <div class="transaction-info">
                                        <p>Total Rides</p>
                                        <span>This Week</span>
                                    </div>
                                </div>
                                <span class="transaction-amount">28</span>
                            </li>
                            <li class="transaction-item">
                                <div class="transaction-details">
                                    <div class="transaction-info">
                                        <p>Total Earnings</p>
                                        <span>This Week</span>
                                    </div>
                                </div>
                                <span class="transaction-amount">₱4,250</span>
                            </li>
                            <li class="transaction-item">
                                <div class="transaction-details">
                                    <div class="transaction-info">
                                        <p>Average per Ride</p>
                                        <span>This Week</span>
                                    </div>
                                </div>
                                <span class="transaction-amount">₱151</span>
                            </li>
                        </ul>
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
