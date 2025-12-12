@extends('layouts.app')

@push('head')
    <style>
        body {
            background: linear-gradient(135deg, #0d37db, #2043b4, #ffffff);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
        }
        
        .container {
            max-width: 1200px;
            width: 100%;
        }
        
        header {
            text-align: center;
            margin-bottom: 3rem;
            color: white;
        }
        
        h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .legal-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .legal-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            text-align: center;
        }
        
        .legal-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }
        
        .legal-card i {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            color: #1a2a6c;
        }
        
        .legal-card h2 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: #1a2a6c;
        }
        
        .legal-card p {
            color: #666;
            margin-bottom: 1.5rem;
        }
        
        .btn {
            display: inline-block;
            background: #1a2a6c;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn:hover {
            background: #0d1a4d;
        }
        
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        
        .popup-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .popup-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
            padding: 2rem;
            position: relative;
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }
        
        .popup-overlay.active .popup-content {
            transform: scale(1);
        }
        
        .close-btn {
            position: absolute;
            top: 1rem;
            right: 1.5rem;
            font-size: 1.8rem;
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
            transition: color 0.3s ease;
        }
        
        .close-btn:hover {
            color: #1a2a6c;
        }
        
        .popup-content h2 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: #1a2a6c;
            padding-right: 2rem;
        }
        
        .popup-content h3 {
            font-size: 1.5rem;
            margin: 1.5rem 0 1rem;
            color: #1a2a6c;
        }
        
        .popup-content p {
            margin-bottom: 1rem;
        }
        
        .popup-content ul {
            margin-left: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .popup-content li {
            margin-bottom: 0.5rem;
        }
        
        footer {
            text-align: center;
            margin-top: auto;
            padding: 2rem 0;
            color: white;
            width: 100%;
        }
        
        footer p {
            opacity: 0.8;
        }
        
        @media (max-width: 768px) {
            h1 {
                font-size: 2.5rem;
            }
            
            .legal-links {
                grid-template-columns: 1fr;
            }
            
            .popup-content {
                padding: 1.5rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <header>
            <h1>{{ __('Legal Information') }}</h1>
            <p class="subtitle">{{ __('Review our policies and guidelines to understand how we operate and protect your data') }}</p>
        </header>
        
        <div class="legal-links">
            <div class="legal-card" data-popup="terms">
                <i class="fas fa-file-contract"></i>
                <h2>{{ __('Terms & Conditions') }}</h2>
                <p>{{ __('Our rules and guidelines for using our services') }}</p>
                <button class="btn">{{ __('View Details') }}</button>
            </div>
            
            <div class="legal-card" data-popup="privacy">
                <i class="fas fa-user-shield"></i>
                <h2>{{ __('Privacy Policy') }}</h2>
                <p>{{ __('How we collect, use, and protect your personal information') }}</p>
                <button class="btn">{{ __('View Details') }}</button>
            </div>
            
            <div class="legal-card" data-popup="data">
                <i class="fas fa-database"></i>
                <h2>{{ __('Data Usage & Storage') }}</h2>
                <p>{{ __('Our policies on data handling and retention') }}</p>
                <button class="btn">{{ __('View Details') }}</button>
            </div>
            
            <div class="legal-card" data-popup="safety">
                <i class="fas fa-shield-alt"></i>
                <h2>{{ __('Safety Guidelines') }}</h2>
                <p>{{ __('Tips and guidelines to ensure your safety while using our services') }}</p>
                <button class="btn">{{ __('View Details') }}</button>
            </div>
        </div>
        
        <footer>
            <p>&copy; 2025 {{ __('HitchHike') }}. {{ __('All rights reserved.') }}</p>
        </footer>
    </div>
    
    <!-- Terms & Conditions Popup -->
    <div class="popup-overlay" id="terms-popup">
        <div class="popup-content">
            <button class="close-btn">&times;</button>
            <h2>{{ __('Terms & Conditions') }}</h2>
            <p>{{ __('Welcome to our platform. By accessing or using our services, you agree to be bound by these Terms and Conditions.') }}</p>
            
            <h3>{{ __('1. Acceptance of Terms') }}</h3>
            <p>{{ __('By using our services, you acknowledge that you have read, understood, and agree to be bound by these Terms.') }}</p>
            
            <h3>{{ __('2. User Responsibilities') }}</h3>
            <p>{{ __('You are responsible for:') }}</p>
            <ul>
                <li>{{ __('Maintaining the confidentiality of your account information') }}</li>
                <li>{{ __('All activities that occur under your account') }}</li>
                <li>{{ __('Complying with all applicable laws and regulations') }}</li>
            </ul>
            
            <h3>{{ __('3. Prohibited Activities') }}</h3>
            <p>{{ __('You may not use our services to:') }}</p>
            <ul>
                <li>{{ __('Engage in any illegal activities') }}</li>
                <li>{{ __('Harass, abuse, or harm others') }}</li>
                <li>{{ __('Distribute malware or harmful code') }}</li>
                <li>{{ __('Violate intellectual property rights') }}</li>
            </ul>
            
            <h3>{{ __('4. Termination') }}</h3>
            <p>{{ __('We reserve the right to suspend or terminate your account at our discretion if you violate these Terms.') }}</p>
            
            <h3>{{ __('5. Changes to Terms') }}</h3>
            <p>{{ __('We may modify these Terms at any time. Continued use of our services after changes constitutes acceptance of the modified Terms.') }}</p>
        </div>
    </div>
    
    <!-- Privacy Policy Popup -->
    <div class="popup-overlay" id="privacy-popup">
        <div class="popup-content">
            <button class="close-btn">&times;</button>
            <h2>{{ __('Privacy Policy') }}</h2>
            <p>{{ __('We are committed to protecting your privacy. This policy explains how we collect, use, and safeguard your information.') }}</p>
            
            <h3>{{ __('1. Information We Collect') }}</h3>
            <p>{{ __('We may collect the following types of information:') }}</p>
            <ul>
                <li>{{ __('Personal information (name, email, etc.) that you provide') }}</li>
                <li>{{ __('Usage data and analytics') }}</li>
                <li>{{ __('Device information and IP addresses') }}</li>
                <li>{{ __('Cookies and similar tracking technologies') }}</li>
            </ul>
            
            <h3>{{ __('2. How We Use Your Information') }}</h3>
            <p>{{ __('We use your information to:') }}</p>
            <ul>
                <li>{{ __('Provide and improve our services') }}</li>
                <li>{{ __('Personalize your experience') }}</li>
                <li>{{ __('Communicate with you about our services') }}</li>
                <li>{{ __('Ensure the security of our platform') }}</li>
            </ul>
            
            <h3>{{ __('3. Information Sharing') }}</h3>
            <p>{{ __('We do not sell your personal information. We may share information with:') }}</p>
            <ul>
                <li>{{ __('Service providers who assist in our operations') }}</li>
                <li>{{ __('Law enforcement when required by law') }}</li>
                <li>{{ __('Other parties with your consent') }}</li>
            </ul>
            
            <h3>{{ __('4. Your Rights') }}</h3>
            <p>{{ __('You have the right to:') }}</p>
            <ul>
                <li>{{ __('Access the personal information we hold about you') }}</li>
                <li>{{ __('Request correction of inaccurate information') }}</li>
                <li>{{ __('Request deletion of your personal information') }}</li>
                <li>{{ __('Opt-out of marketing communications') }}</li>
            </ul>
        </div>
    </div>
    
    <!-- Data Usage & Storage Popup -->
    <div class="popup-overlay" id="data-popup">
        <div class="popup-content">
            <button class="close-btn">&times;</button>
            <h2>{{ __('Data Usage & Storage Policy') }}</h2>
            <p>{{ __('This policy outlines how we handle, store, and protect your data.') }}</p>
            
            <h3>{{ __('1. Data Collection') }}</h3>
            <p>{{ __('We collect data necessary to provide our services, including:') }}</p>
            <ul>
                <li>{{ __('Account information') }}</li>
                <li>{{ __('Service usage data') }}</li>
                <li>{{ __('Communication records') }}</li>
                <li>{{ __('Technical information about your device') }}</li>
            </ul>
            
            <h3>{{ __('2. Data Storage') }}</h3>
            <p>{{ __('Your data is stored securely using industry-standard measures:') }}</p>
            <ul>
                <li>{{ __('Encryption of data in transit and at rest') }}</li>
                <li>{{ __('Secure data centers with physical access controls') }}</li>
                <li>{{ __('Regular security audits and monitoring') }}</li>
            </ul>
            
            <h3>{{ __('3. Data Retention') }}</h3>
            <p>{{ __('We retain your data only as long as necessary:') }}</p>
            <ul>
                <li>{{ __('Account data: Until account deletion') }}</li>
                <li>{{ __('Usage data: Up to 24 months') }}</li>
                <li>{{ __('Communication records: Up to 36 months') }}</li>
                <li>{{ __('Backup data: Up to 90 days after deletion') }}</li>
            </ul>
            
            <h3>{{ __('4. Data Security') }}</h3>
            <p>{{ __('We implement comprehensive security measures including:') }}</p>
            <ul>
                <li>{{ __('Regular security assessments') }}</li>
                <li>{{ __('Access controls and authentication') }}</li>
                <li>{{ __('Incident response procedures') }}</li>
                <li>{{ __('Employee security training') }}</li>
            </ul>
            
            <h3>{{ __('5. International Data Transfers') }}</h3>
            <p>{{ __('If we transfer your data internationally, we ensure adequate protection through appropriate safeguards.') }}</p>
        </div>
    </div>
    
    <!-- Safety Guidelines Popup -->
    <div class="popup-overlay" id="safety-popup">
        <div class="popup-content">
            <button class="close-btn">&times;</button>
            <h2>{{ __('Safety Guidelines') }}</h2>
            <p>{{ __('Your safety is important to us. Follow these guidelines to protect yourself while using our services.') }}</p>
            
            <h3>{{ __('1. Account Security') }}</h3>
            <p>{{ __('Protect your account with these practices:') }}</p>
            <ul>
                <li>{{ __('Use a strong, unique password') }}</li>
                <li>{{ __('Enable two-factor authentication if available') }}</li>
                <li>{{ __('Never share your login credentials') }}</li>
                <li>{{ __('Log out from shared devices') }}</li>
            </ul>
            
            <h3>{{ __('2. Personal Information') }}</h3>
            <p>{{ __('Be cautious about what information you share:') }}</p>
            <ul>
                <li>{{ __('Limit sharing of personal contact information') }}</li>
                <li>{{ __('Be wary of requests for financial information') }}</li>
                <li>{{ __('Think carefully before sharing location data') }}</li>
                <li>{{ __('Report suspicious requests to our support team') }}</li>
            </ul>
            
            <h3>{{ __('3. Meeting Others') }}</h3>
            <p>{{ __('If our service involves meeting others:') }}</p>
            <ul>
                <li>{{ __('Meet in public, well-lit areas') }}</li>
                <li>{{ __('Tell a friend or family member about your plans') }}</li>
                <li>{{ __('Arrange your own transportation') }}</li>
                <li>{{ __('Trust your instincts - if something feels wrong, leave') }}</li>
            </ul>
            
            <h3>{{ __('4. Reporting Concerns') }}</h3>
            <p>{{ __('Report any safety concerns immediately:') }}</p>
            <ul>
                <li>{{ __('Use our in-app reporting features') }}</li>
                <li>{{ __('Contact our support team with details') }}</li>
                <li>{{ __('In case of emergency, contact local authorities') }}</li>
            </ul>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const legalCards = document.querySelectorAll('.legal-card');
            const popupOverlays = document.querySelectorAll('.popup-overlay');
            const closeButtons = document.querySelectorAll('.close-btn');
            
            legalCards.forEach(card => {
                card.addEventListener('click', function() {
                    const popupId = this.getAttribute('data-popup') + '-popup';
                    const popup = document.getElementById(popupId);
                    
                    if (popup) {
                        popup.classList.add('active');
                        document.body.style.overflow = 'hidden';
                    }
                });
            });
            
            // Add click event to close buttons
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const popup = this.closest('.popup-overlay');
                    popup.classList.remove('active');
                    document.body.style.overflow = 'auto';
                });
            });
            
            popupOverlays.forEach(overlay => {
                overlay.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.classList.remove('active');
                        document.body.style.overflow = 'auto'; 
                    }
                });
            });
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    popupOverlays.forEach(overlay => {
                        overlay.classList.remove('active');
                    });
                    document.body.style.overflow = 'auto'; 
                }
            });
        });
    </script>
@endpush
