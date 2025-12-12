<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hitchhike - Shared Rides, Better Journeys</title>
    <link rel="icon" type="image/x-icon" href="{{asset('favicon.ico')}}">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/css/landingpage.css'])
</head>
<body>

{{-- HEADER --}}
<header class="main-header">
    <div class="header-logo">
        <img src="{{Vite::asset('resources/img/Hitchhike Logo.png')}}" alt="Hitchhike Logo">
        <span>Hitchhike</span>
    </div>

    <div>
        <span class="separator">|</span>
    </div>

    <nav class="header-nav">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('home') }}#book">Book a Ride</a>
        <a href="#about">About Us</a>
        <a href="{{ url('about') }}">Contact</a>
    </nav>

    <div class="header-auth">
        @auth
            <a href="{{ route('home') }}" class="login-btn">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="signup-btn" style="background: transparent; color: var(--primary); border: 2px solid var(--primary);">Log Out</button>
            </form>
        @else
            <a href="{{ url('login') }}" class="login-btn">Log In</a>
            <a href="{{ url('register') }}" class="signup-btn">Sign Up</a>
        @endauth
    </div>
</header>

{{-- SECTION 1 - HERO --}}
<section class="section1">
    <div class="section1-content">
        <h1 class="reveal fade-up">Shared Rides,<br>Better Journeys</h1>
        <h2 class="reveal fade-left">
            Safe, convenient, and community-driven rides for everyone.
        </h2>
        <p class="section1-body">
            Hitchhike connects drivers and passengers heading the same way — giving people the freedom to travel more
            efficiently, save money, and make meaningful connections on the road. Whether you need an urgent ride or
            want to earn extra income while driving, Hitchhike makes it easy to offer or join rides safely and
            fairly.
        </p>
        <a href="{{ url('register') }}" class="section1-btn reveal scale-up">Get Started!</a>
    </div>

    <img src="{{Vite::asset('resources/img/Background Img.png')}}" class="section1-img reveal fade-right" alt="Hitchhike Hero">
</section>

{{-- SECTION 2 - FEATURES --}}
<section class="section2">
    <div class="section2-header reveal fade-up">
        <h1>What Makes Hitchhike Different</h1>
        <p class="section2-subtitle">
            Discover why Hitchhike stands out for drivers and passengers alike.
        </p>
    </div>

    <div class="section2-features">
        <div class="feature reveal fade-left">
            <div class="icon"><i class="fa-solid fa-route"></i></div>
            <h2>Freedom and Flexibility</h2>
            <p>
                With Hitchhike, you're in control. Riders can request rides on demand, 
                and drivers can offer seats to passengers traveling in the same direction. 
                Agree on a fair contribution that works for both sides — no hidden costs, 
                no rigid pricing.
            </p>
        </div>

        <div class="feature reveal fade-up">
            <div class="icon"><i class="fa-solid fa-bolt"></i></div>
            <h2>Quick and Affordable Travel</h2>
            <p>
                Get where you need to go faster. Find rides for immediate or scheduled trips, 
                whether it's within the city or to another province. Hitchhike is your go-to 
                platform for urgent travel needs and long-distance journeys alike.
            </p>
        </div>

        <div class="feature reveal fade-right">
            <div class="icon"><i class="fa-solid fa-gas-pump"></i></div>
            <h2>Earn While You Travel</h2>
            <p>
                Drivers can offset fuel costs or earn extra cash by offering seats on their trips. 
                Passengers can enjoy budget-friendly travel without compromising comfort or safety. 
                It's a win-win for everyone on the road.
            </p>
        </div>
    </div>
</section>

{{-- SECTION 3 - HOW IT WORKS --}}
<section class="section3 reveal fade-up">
    <div class="section3-header">
        <h1>⚙️ How Hitchhike Works</h1>
        <p>Your journey made simple, safe, and seamless.</p>
    </div>

    <div class="section3-wrapper">
        <div class="section3-steps reveal">
            <div class="step fade-diagonal reveal">
                <div class="icon">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <div class="step-text">
                    <h2>Sign Up & Create Your Profile</h2>
                    <p>Build trust with verified accounts, ratings, and reviews.</p>
                </div>
            </div>

            <div class="step fade-diagonal reveal">
                <div class="icon">
                    <i class="fa-solid fa-route"></i>
                </div>
                <div class="step-text">
                    <h2>Find or Offer a Ride</h2>
                    <p>Search routes using interactive tools — or post your own ride.</p>
                </div>
            </div>

            <div class="step fade-diagonal reveal">
                <div class="icon">
                    <i class="fa-solid fa-handshake"></i>
                </div>
                <div class="step-text">
                    <h2>Agree on Fair Pricing</h2>
                    <p>Transparent and flexible — you and the driver choose the deal.</p>
                </div>
            </div>

            <div class="step fade-diagonal reveal">
                <div class="icon">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <div class="step-text">
                    <h2>Connect & Go!</h2>
                    <p>Chat right on the site, finalize details, and hit the road.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 4 - TRUST --}}
<section class="section4">
    <div class="container">
        <h2 class="reveal">A Community Built on Trust</h2>
        <p class="subtitle reveal">At Hitchhike, we believe safety and reputation come first.</p>

        <div class="trust-grid">
            <div class="trust-items">
                <div class="trust-item reveal">
                    <h3>Verified Users</h3>
                    <p>Each profile is authenticated for accountability.</p>
                </div>
                <div class="trust-item reveal">
                    <h3>Ratings & Reviews</h3>
                    <p>Build your reputation as a trusted driver or reliable passenger.</p>
                </div>
                <div class="trust-item reveal">
                    <h3>Open Communication</h3>
                    <p>Coordinate rides and share details directly within the platform.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 5 - MAP --}}
<section class="section5">
    <h2 class="section5-title">🗺️ Smart Navigation, Smarter Choices</h2>

    <div class="map-wrapper">
        <div class="map-track">
            <img src="{{ asset('storage/wide-map.png') }}" class="wide-map" alt="Map">
        </div>

        <div class="map-points-row">
            <div class="map-point point1">
                <h3>Nearby Rides</h3>
                <p>View real-time ride availability.</p>
            </div>

            <div class="map-point point2">
                <h3>Smart Filters</h3>
                <p>Sort by distance, time or fare.</p>
            </div>

            <div class="map-point point3">
                <h3>Popular Routes</h3>
                <p>Discover trending destinations.</p>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 6 - CTA --}}
<section class="section6" id="about">
    <div class="container">
        <h2 class="reveal">🌍 Ride Together, Go Further</h2>
        <p class="subtitle reveal">
            Hitchhike isn't just about getting from A to B — it's about making travel more accessible,
            sustainable, and community-driven. Join thousands of drivers and riders choosing a smarter way to move.
        </p>
        <a href="{{ url('register') }}" class="cta-btn reveal">Join Hitchhike Today!</a>
        <p class="cta-subtext reveal">Start sharing rides — and make every journey count</p>
    </div>
</section>

{{-- FOOTER --}}
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Hitchhike</h3>
                <ul class="footer-links">
                    <li><a href="{{ url('about') }}">About Us</a></li>
                    <li><a href="#">Careers</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Product</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">Book a Ride</a></li>
                    <li><a href="{{ route('ride.create') }}">Offer a Ride</a></li>
                    <li><a href="#how-it-works">How it works</a></li>
                    <li><a href="#">Safety</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Support</h3>
                <ul class="footer-links">
                    <li><a href="#">Help Center</a></li>
                    <li><a href="{{ url('about') }}">Contact Us</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Stay Updated</h3>
                <p>Follow us on social media</p>
                <div class="social-list">
                    <a href="#" class="social-link"><i class="fa-brands fa-facebook-f"></i> Facebook</a>
                    <a href="#" class="social-link"><i class="fa-brands fa-instagram"></i> Instagram</a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 Hitchhike. | <a href="#">Privacy Policy</a> | <a href="#">Terms and Conditions</a></p>
        </div>
    </div>
</footer>

@vite(['resources/js/landingpage.js'])

<script>
    // Intersection Observer for reveal animations
    const revealElements = document.querySelectorAll('.reveal');
    
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('section-active');
            }
        });
    }, { threshold: 0.1 });
    
    revealElements.forEach(el => revealObserver.observe(el));
</script>

</body>
</html>
