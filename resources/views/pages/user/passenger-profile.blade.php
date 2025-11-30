@extends('layouts.app')

@push('head')
    @vite(['resources/css/app.css', 'resources/js/profile.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
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
                        <h3 id="user-name">{{ Auth::user()->name ?? 'User Name' }}</h3>
                        <a href="/profile" class="edit-profile">Edit profile  >  </a>
                    </div>
                </div>

                <div class="sidebar-section">
                    <a href="/dashboard" class="sidebar-menu-links">Dashboard</a>
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

<div class="profile-container">
    <div class="profile-sidebar">
        <div class="profile-photo">
            <img src="{{ Vite::asset('resources/img/placeholder_profile.png') }}" alt="Profile Photo" id="profilePhoto">
            <button class="update-photo-btn">Update Photo</button>
        </div>
        <nav class="profile-nav">
            <button class="nav-item active" data-section="personal">Profile Info</button>
            <button class="nav-item" data-section="security">Security</button>
        </nav>
        <div class="profile-submit-container">
            <button id="submitProfileBtn" class="save-btn">Submit Profile</button>
        </div>
    </div>

    <div class="profile-content">
        <section id="personal" class="profile-section active">
            <h2>Profile Information</h2>
            <form id="profileInfoForm" method="POST" action="{{ route('user.update', ['user' => Auth::id()]) }}">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label for="fullName">Full Name</label>
                    <input type="text" id="fullName" name="fullName" value="{{ Auth::user()->name ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ Auth::user()->email ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ Auth::user()->phone ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" required>{{ Auth::user()->address ?? '' }}</textarea>
                </div>
                <button type="submit" class="save-btn">Save Changes</button>
            </form>
        </section>

        <section id="security" class="profile-section">
            <h2>Security Settings</h2>
            <form id="securityForm" method="POST">
                @csrf
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
