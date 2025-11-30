@extends('layouts.app')

@push('head')
    @vite(['resources/css/app.css', 'resources/js/profile.js'])
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
        .main-content {
            padding: 0;
            background-color: var(--background);
        }
        body.dark-mode .main-content {
            background-color: var(--background-dark);
        }
        .profile-container {
            display: flex;
            width: 100%;
            min-height: 80vh;
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin: 20px;
            overflow: hidden;
        }
        body.dark-mode .profile-container {
            background-color: #1a243d;
            border: 1px solid #334155;
        }
        .profile-sidebar {
            width: 280px;
            padding: 30px 24px;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
        }
        body.dark-mode .profile-sidebar {
            border-right-color: #334155;
        }
        .profile-photo {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-photo img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: var(--primary-light);
            border: 4px solid var(--card-bg);
            box-shadow: var(--shadow);
            margin-bottom: 15px;
        }
        .update-photo-btn {
            background: none;
            border: 1px solid var(--border);
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            color: var(--text-dark);
        }
        body.dark-mode .update-photo-btn {
            color: var(--text-light);
            border-color: #334155;
        }
        .update-photo-btn:hover {
            background-color: var(--background-hover);
        }
        .profile-nav {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }
        .nav-item {
            padding: 14px 20px;
            border: none;
            background: var(--background-hover);
            border-radius: 8px;
            text-align: left;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-dark);
        }
        body.dark-mode .nav-item {
            background-color: #334155;
            color: var(--text-light);
        }
        .nav-item.active {
            background: var(--primary);
            color: white;
        }
        body.dark-mode .nav-item.active {
            background: var(--primary-light);
        }
        .nav-item:hover {
            opacity: 0.9;
        }
        .profile-submit-container {
            margin-top: auto;
            padding-top: 30px;
        }
        .save-btn {
            width: 100%;
            padding: 14px;
            background: var(--primary-light);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .save-btn:hover {
            background: var(--primary);
        }
        .profile-content {
            flex: 1;
            padding: 30px 40px;
            overflow-y: auto;
        }
        .profile-section {
            display: none;
        }
        .profile-section.active {
            display: block;
        }
        .profile-content h2 {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-top: 0;
            margin-bottom: 30px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 15px;
        }
        body.dark-mode .profile-content h2 {
            color: white;
            border-bottom-color: #334155;
        }
        .form-group {
            margin-bottom: 24px;
        }
        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--text-dark);
        }
        body.dark-mode .form-group label {
            color: var(--text-light);
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group input[type="password"],
        .form-group input[type="date"],
        .form-group input[type="number"],
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            background: var(--card-bg);
            color: var(--text-dark);
        }
        body.dark-mode .form-group input,
        body.dark-mode .form-group textarea {
            background: #334155;
            border-color: #4b5563;
            color: white;
        }
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        .document-upload {
            border: 2px dashed var(--border);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .document-upload label {
            font-weight: 600;
        }
        .document-upload input[type="file"] {
            margin-top: 10px;
        }
        body.dark-mode .document-upload {
            border-color: #334155;
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
                <a href="/driver/earnings" class="driver-nav-link">
                    <i class="fa-solid fa-wallet"></i> Earnings
                </a>
                <a href="/driver/notifications" class="driver-nav-link">
                    <i class="fa-solid fa-bell"></i> Notifications
                </a>
                <a href="/driver/profile" class="driver-nav-link active">
                    <i class="fa-solid fa-user"></i> Profile
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="profile-container">
                <div class="profile-sidebar">
                    <div class="profile-photo">
                        <img src="{{ Vite::asset('resources/img/Driver Icon.png') }}" alt="Profile Photo" id="profilePhoto">
                        <button class="update-photo-btn">Update Photo</button>
                    </div>
                    <nav class="profile-nav">
                        <button class="nav-item active" data-section="personal">Personal Info</button>
                        <button class="nav-item" data-section="vehicle">Vehicle Info</button>
                        <button class="nav-item" data-section="documents">License & ORCR</button>
                        <button class="nav-item" data-section="security">Security</button>
                    </nav>
                    <div class="profile-submit-container">
                        <button id="submitProfileBtn" class="save-btn">Save All Changes</button>
                    </div>
                </div>

                <div class="profile-content">
                    <section id="personal" class="profile-section active">
                        <h2>Personal Information</h2>
                        <form id="personalInfoForm">
                            <div class="form-group">
                                <label for="fullName">Full Name</label>
                                <input type="text" id="fullName" name="fullName" value="{{ Auth::user()->name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea id="address" name="address" placeholder="Enter your full address" required></textarea>
                            </div>
                            <button type="submit" class="save-btn">Save Personal Info</button>
                        </form>
                    </section>

                    <section id="vehicle" class="profile-section">
                        <h2>Vehicle Information</h2>
                        <form id="vehicleInfoForm">
                            <div class="form-group">
                                <label for="vehicleModel">Vehicle Model</label>
                                <input type="text" id="vehicleModel" name="vehicleModel" placeholder="e.g., Toyota Vios" required>
                            </div>
                            <div class="form-group">
                                <label for="plateNumber">Plate Number</label>
                                <input type="text" id="plateNumber" name="plateNumber" placeholder="e.g., ABC 1234" required>
                            </div>
                            <div class="form-group">
                                <label for="vehicleColor">Vehicle Color</label>
                                <input type="text" id="vehicleColor" name="vehicleColor" placeholder="e.g., Silver" required>
                            </div>
                            <div class="form-group">
                                <label for="vehicleYear">Year</label>
                                <input type="number" id="vehicleYear" name="vehicleYear" placeholder="e.g., 2022" required>
                            </div>
                            <button type="submit" class="save-btn">Save Vehicle Info</button>
                        </form>
                    </section>

                    <section id="documents" class="profile-section">
                        <h2>License & ORCR</h2>
                        <form id="documentsForm">
                            <div class="form-group">
                                <label for="licenseNumber">License Number</label>
                                <input type="text" id="licenseNumber" name="licenseNumber" required>
                            </div>
                            <div class="form-group">
                                <label for="licenseExpiry">License Expiry Date</label>
                                <input type="date" id="licenseExpiry" name="licenseExpiry" required>
                            </div>
                            <div class="form-group">
                                <label for="orcrNumber">ORCR Number</label>
                                <input type="text" id="orcrNumber" name="orcrNumber" required>
                            </div>
                            <div class="document-upload">
                                <label>Upload License Photo</label>
                                <input type="file" id="licensePhoto" accept="image/*">
                            </div>
                            <div class="document-upload">
                                <label>Upload ORCR Photo</label>
                                <input type="file" id="orcrPhoto" accept="image/*">
                            </div>
                            <button type="submit" class="save-btn">Save Documents</button>
                        </form>
                    </section>

                    <section id="security" class="profile-section">
                        <h2>Security Settings</h2>
                        <form id="securityForm">
                            <div class="form-group">
                                <label for="currentPassword">Current Password</label>
                                <input type="password" id="currentPassword" name="currentPassword" required>
                            </div>
                            <div class="form-group">
                                <label for="newPassword">New Password</label>
                                <input type="password" id="newPassword" name="newPassword" required>
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword">Confirm New Password</label>
                                <input type="password" id="confirmPassword" name="confirmPassword" required>
                            </div>
                            <button type="submit" class="save-btn">Update Password</button>
                        </form>
                    </section>
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
