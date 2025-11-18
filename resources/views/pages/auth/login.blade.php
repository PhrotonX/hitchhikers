@extends('layouts.app')

{{-- login styles moved to resources/css/login.css and are loaded only on this page --}}
@push('head')
    {{-- Inline login styles directly in the view for guaranteed immediate loading --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body{
            background: #f9fafb;
            font-family: 'Poppins', sans-serif;
        }
        .auth-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 24px rgba(18, 18, 18, 0.08);
            padding: 28px;
        }
        .auth-title {
            font-size: 1.4rem;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }
        .auth-sub {
            color: #6b7280;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 12px;
        }
        .form-group label {
            font-size: 0.85rem;
            margin-bottom: 6px;
            color: #374151;
        }
        .form-control {
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.95rem;
        }
        .form-control:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.08);
        }
        .form-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 14px;
        }
        .btn {
            background: #6366f1;
            color: #fff;
            padding: 10px 14px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }
        .link-btn {
            background: transparent;
            border: none;
            color: #6366f1;
            cursor: pointer;
        }
        .error-msg {
            color: #b91c1c;
            font-size: 0.9rem;
            margin-top: 6px;
        }
        @media (max-width: 480px) {
            .auth-card {
                padding: 20px;
                margin: 8px;
            }
        }
    </style>
    
@endpush


@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-title">Welcome back</div>
            <div class="auth-sub">Sign in to your account to continue to Hitchhikers</div>

            @if(session('status'))
                <div class="error-msg">{{ session('status') }}</div>
            @endif

            <form action="{{ url('login') }}" method="POST" novalidate>
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus aria-required="true" aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">
                    @if($errors->has('email'))
                        <div class="error-msg">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div style="display:flex;gap:8px;align-items:center;">
                        <input id="password" class="form-control" type="password" name="password" required aria-required="true" aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}">
                        <button type="button" id="togglePassword" class="link-btn" aria-label="Toggle password visibility">Show</button>
                    </div>
                    @if($errors->has('password'))
                        <div class="error-msg">{{ $errors->first('password') }}</div>
                    @endif
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:6px">
                    <label style="display:flex;align-items:center;gap:8px"><input type="checkbox" name="remember"> Remember me</label>
                    <a href="{{ url('forgot-password') }}" class="link-btn">{{ __('credentials.forgot_password') }}</a>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn">Sign in</button>
                    <button type="button" onclick="window.location.href='{{ url('/register') }}'" class="link-btn">Create account</button>
                </div>
            </form>
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
            const signInBtn = loginForm?.querySelector('button[type="submit"]');
            
            if(loginForm && signInBtn){
                loginForm.addEventListener('submit', function(e){
                    // Form will submit naturally; optionally add loading state
                    signInBtn.disabled = true;
                    signInBtn.textContent = 'Signing in...';
                    
                    // Note: Form submits to POST /login via Laravel route
                    // AuthenticatedSessionController::store() validates credentials
                    // On success: redirects to /home
                    // On failure: redirects back with $errors and old('email')
                });
            }

            // Create account button - navigate to registration
            const createAccountBtn = document.querySelector('button[onclick*="register"]');
            if(createAccountBtn){
                createAccountBtn.addEventListener('click', function(){
                    window.location.href = '{{ url("register") }}';
                });
            }
        })();
    </script>
    @endpush

@endsection