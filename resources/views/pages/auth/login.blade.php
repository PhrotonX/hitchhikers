@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
        /*
         * 1. CSS Variables Definition (Minimized to used variables only)
         * 2. General Body and Layout Reset
         * 3. Navigation Bar Fix (New)
         * 4. Advanced Component Styles and Responsiveness
         * 5. Error/Status Messages
         */

        /* ---------------------- 1. CSS VARIABLES (MINIMIZED) ---------------------- */
        :root {
            /* Light Mode Colors (Used Only) */
            --primary: #1E3A8A;
            --primary-light: #3B5FCF;
            --secondary: #10B981;
            --error: #EF4444;
            --background: #F9FAFB;
            --text-dark: #1F2937;
            --text-light: #6B7280;
            --border: #E5E7EB;
            --shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        /* ---------------------- 2. GENERAL STYLES ---------------------- */
        body {
            background: var(--background);
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden; 
            opacity: 1;
            transition: opacity 0.8s ease;
        }

        /* ---------------------- 3. NAVIGATION BAR FIX (Targeting the "Get Started" cutoff) ---------------------- */
        /* This assumes the main navigation bar is a top-level element that needs full width and proper padding. */
        .main-navigation-bar { 
            width: 100%;
            padding-left: clamp(16px, 4vw, 40px);
            padding-right: clamp(16px, 4vw, 40px);
            box-sizing: border-box;
            /* Ensure the button is fully visible, especially on mobile */
        }
        
        /* ---------------------- 4. AUTH PAGE LAYOUT ---------------------- */
        .auth-page {
            /* Using a placeholder image for the background */
            background-image: url('../../../img/Background Img.png'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            padding-top: clamp(24px, 6vh, 48px);
            padding-bottom: clamp(24px, 6vh, 48px); /* Added bottom padding to center content vertically */
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-y: auto; /* Allow scroll if content is too tall */
        }

        /* Container - Handles centering and max-width. */
        .auth-page .container {
            width: 80vw;
            margin: 0 auto;
            padding: 0;
            gap: 20px;
            display: flex; /* Make container flexible for centering */
            justify-content: center;
            align-items: center;
        }

        /* Login Card */
        .auth-page .login-card {
            background-color: rgba(255, 255, 255, 0.95); /* Increased opacity slightly */
            border-radius: clamp(12px, 2vw, 16px);
            padding: clamp(20px, 4vw, 32px);
            box-shadow: var(--shadow);
            width: 80vw; /* Default width for mobile */
            border: 1px solid var(--border);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeInUp 0.5s ease forwards;
        }

        .auth-page .login-card:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-2px);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Header */
        .auth-page .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-page .login-header h1 {
            font-size: clamp(20px, 5vw, 28px);
            margin-bottom: 8px;
            color: var(--text-dark);
            font-weight: 600;
        }

        .auth-page .subtitle {
            color: var(--text-light);
            font-size: clamp(13px, 3vw, 15px);
        }

        /* Input Group */
        .login-form {
            display: flex;
            flex-direction: column;
        }
        
        .auth-page .input-group {
            position: relative;
            margin-bottom: 20px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0; 
            transition: all 0.2s ease;
        }

        /* Icon positioning for the new style */
        .auth-page .input-group i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            margin-right: 0; 
            font-size: 1.1rem;
            z-index: 10;
        }

        /* Input Field */
        .auth-page .input-group input {
            width: 100%;
            padding: clamp(10px, 2vw, 12px) clamp(36px, 8vw, 40px);
            border: none;
            background-color: transparent;
            border-radius: 8px;
            color: var(--text-dark);
            font-size: clamp(14px, 2vw, 16px);
            transition: all 0.2s ease;
            font-family: inherit;
            outline: none;
        }

        .auth-page .input-group:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(30, 58, 138, 0.15); 
        }

        /* Password Toggle */
        .password-toggle {
            background: transparent;
            border: none;
            color: var(--primary);
            cursor: pointer;
            font-size: 0.85rem;
            padding: 0 12px 0 10px; 
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            user-select: none;
        }

        /* Form Options & Forgot Password */
        .auth-page .form-options {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-top: -5px; 
            margin-bottom: 20px;
            font-size: 14px;
        }
        .auth-page .forgot-password {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.85rem; 
            transition: opacity 0.3s ease;
        }
        .auth-page .forgot-password:hover {
            opacity: 0.8;
            color: var(--primary-light);
        }

        /* Login Button */
        .auth-page .login-btn {
            width: 100%;
            background-color: var(--primary);
            color: white;
            border: none;
            padding: clamp(12px, 2.5vw, 14px);
            border-radius: 8px;
            font-size: clamp(14px, 2.5vw, 16px);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: clamp(16px, 4vw, 20px);
            position: relative;
            overflow: hidden;
        }
        .auth-page .login-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        .auth-page .login-btn:hover::after {
            transform: translateX(0);
        }
        .auth-page .login-btn:hover {
            background-color: var(--primary-light);
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }
        .auth-page .login-btn:active {
            transform: translateY(0);
        }


        /* Register Link Container */
        .register-link-container {
            margin-top: 15px;
            font-size: 14px; 
            color: var(--text-light);
            text-align: center;
        }
        .register-link-container button {
            background: none;
            border: none;
            padding: 0;
            color: var(--primary);
            cursor: pointer;
            font-weight: 600;
            font-family: inherit;
        }

        /* ---------------------- 5. ERROR/STATUS MESSAGES ---------------------- */

        .error-msg {
            color: var(--error);
            font-size: 0.9rem;
            margin-top: 6px;
            text-align: left;
        }
        .status-msg {
            color: var(--secondary); 
            font-size: 0.9rem;
            margin-bottom: 12px;
            text-align: left;
            padding: 8px 12px;
            background-color: #ECFDF5;
            border-radius: 4px;
            border: 1px solid var(--secondary);
        }
        .validation-error-below-input {
            margin-top: -15px; 
            margin-bottom: 10px;
        }
        
        /* ---------------------- 6. RESPONSIVE DESIGN ---------------------- */

        /* Reduced motion preferences */
        @media (prefers-reduced-motion: reduce) {
            .auth-page .login-card,
            .auth-page .input-group input,
            .auth-page .login-btn,
            .auth-page .social-btn {
                transition: none;
                animation: none;
            }
            .auth-page .login-btn::after {
                display: none;
            }
        }
    </style>
    
@endpush


@section('content')
    {{-- Assuming the layout wraps the entire navigation bar and content. --}}
    
    <div class="auth-page">
        {{-- The .container handles the responsiveness (max-width: 90%) and centering (margin: 0 auto) --}}
        <div class="container"> 
            <div class="login-card"> 
                <div class="login-header">
                    <h1>Log In</h1>
                    <p class="subtitle">Sign in to continue your journey</p>
                </div>

                @if(session('status'))
                    <div class="status-msg">{{ session('status') }}</div>
                @endif

                <form action="{{ url('login') }}" method="POST" novalidate class="login-form">
                    @csrf

                    {{-- Email Input --}}
                    <div class="input-group @if($errors->has('email')) input-group-error @endif">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="Email"
                               value="{{ old('email') }}" required autofocus
                               aria-required="true" aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">
                    </div>
                    @if($errors->has('email'))
                        <div class="error-msg validation-error-below-input">{{ $errors->first('email') }}</div>
                    @endif
                    
                    {{-- Password Input --}}
                    <div class="input-group @if($errors->has('password')) input-group-error @endif" style="margin-bottom: 5px;">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Password"
                               required aria-required="true" aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}">
                        <button type="button" id="togglePassword" class="password-toggle" aria-label="Toggle password visibility">Show</button>
                    </div>
                    @if($errors->has('password'))
                        <div class="error-msg validation-error-below-input">{{ $errors->first('password') }}</div>
                    @endif
                    
                    {{-- Form Options --}}
                    <div class="form-options">
                        <a href="{{ url('forgot-password') }}" class="forgot-password">{{ __('credentials.forgot_password') }}</a>
                    </div>

                    {{-- Login Button --}}
                    <button type="submit" class="login-btn">Log In</button>
                </form>

                {{-- Create Account Link --}}
                <div class="register-link-container">
                    Don't have an account? 
                    <button type="button" onclick="window.location.href='{{ url('/register') }}'">Create account</button>
                </div>
                
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function(){
            // Password visibility toggle
            const pwd = document.getElementById('password');
            const toggleBtn = document.getElementById('togglePassword');
            if(pwd && toggleBtn){
                toggleBtn.addEventListener('click', function(e){
                    e.preventDefault();
                    if(pwd.type === 'password'){
                        pwd.type = 'text';
                        toggleBtn.textContent = 'Hide';
                    } else {
                        pwd.type = 'password';
                        toggleBtn.textContent = 'Show';
                    }
                });
            }

            // Login form submission
            const loginForm = document.querySelector('form[action*="login"]');
            const signInBtn = loginForm?.querySelector('.login-btn');
            
            if(loginForm && signInBtn){
                loginForm.addEventListener('submit', function(e){
                    signInBtn.disabled = true;
                    signInBtn.textContent = 'Signing in...';
                });
            }

            // Create account button - navigate to registration
            const createAccountBtn = document.querySelector('.register-link-container button');
            if(createAccountBtn){
                createAccountBtn.addEventListener('click', function(){
                    window.location.href = '{{ url("register") }}';
                });
            }
        })();
    </script>
    @endpush

@endsection