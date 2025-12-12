@props([
    'gender' => [
        'male' => __('gender.male'),
        'female' => __('gender.female'),
    ],
])

@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #1E3A8A;
            --primary-light: #3B5FCF;
            --text-dark: #1F2937;
            --text-light: #6B7280;
            --border: #E5E7EB;
            --card-bg: white;
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --error: #EF4444;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .auth-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .signup-card {
            background-color: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 40px;
            box-shadow: var(--shadow);
            max-width: 800px;
            width: 100%;
            border: 1px solid var(--border);
        }

        /* Progress Bar */
        .progress-container {
            width: 100%;
            background-color: var(--border);
            border-radius: 8px;
            height: 8px;
            margin-bottom: 24px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            width: 100%;
            background-color: var(--primary);
            transition: width 0.3s ease;
            border-radius: 8px;
        }

        .signup-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .signup-header h1 {
            font-size: 32px;
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 8px;
        }

        .signup-header p {
            color: var(--text-light);
            font-size: 16px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-dark);
            font-size: 14px;
            font-weight: 500;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            background-color: white;
            border-radius: 8px;
            color: var(--text-dark);
            font-size: 16px;
            transition: all 0.2s ease;
            font-family: inherit;
            box-sizing: border-box;
        }

        .input-group input:focus,
        .input-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.15);
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .submit-btn:hover {
            background-color: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .error-message {
            background-color: #FEE2E2;
            border: 1px solid var(--error);
            color: var(--error);
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .auth-footer {
            text-align: center;
            margin-top: 20px;
            color: var(--text-light);
            font-size: 14px;
        }

        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
@endpush

@section('content')
<div class="auth-container">
    <div class="signup-card">
        <div class="signup-header">
            <h1><i class="fas fa-user-plus"></i> Create Account</h1>
            <p>Join Hitchhike and start your journey</p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-container">
            <div class="progress-bar"></div>
        </div>

        @if($errors->any())
            <div class="error-message">
                <strong><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</strong>
                <ul style="margin: 8px 0 0 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="register" method="POST" id="registerForm">
            @method('POST')
            @csrf

            <div class="form-grid">
                <div class="input-group">
                    <label><i class="fas fa-user"></i> First Name *</label>
                    <input type="text" name="first_name" required value="{{old('first_name')}}" placeholder="Enter first name">
                </div>

                <div class="input-group">
                    <label><i class="fas fa-user"></i> Middle Name</label>
                    <input type="text" name="middle_name" value="{{old('middle_name')}}" placeholder="Enter middle name">
                </div>

                <div class="input-group">
                    <label><i class="fas fa-user"></i> Last Name *</label>
                    <input type="text" name="last_name" required value="{{old('last_name')}}" placeholder="Enter last name">
                </div>

                <div class="input-group">
                    <label><i class="fas fa-user"></i> Ext. Name</label>
                    <input type="text" name="ext_name" value="{{old('ext_name')}}" placeholder="Jr., Sr., III, etc.">
                </div>

                <div class="input-group">
                    <label><i class="fas fa-venus-mars"></i> Gender *</label>
                    <select name="gender" required>
                        <option value="">Select gender</option>
                        @foreach ($gender as $key => $value)
                            <option value="{{$key}}" {{ old('gender') == $key ? 'selected' : '' }}>{{$value}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="input-group">
                    <label><i class="fas fa-calendar"></i> Birthdate *</label>
                    <input type="date" name="birthdate" required value="{{old('birthdate')}}">
                </div>

                <div class="input-group full-width">
                    <label><i class="fas fa-envelope"></i> Email *</label>
                    <input type="email" name="email" required value="{{old('email')}}" placeholder="your.email@example.com">
                </div>

                <div class="input-group full-width">
                    <label><i class="fas fa-phone"></i> Phone *</label>
                    <input type="tel" name="phone" required value="{{old('phone')}}" placeholder="+63 912 345 6789">
                </div>

                <div class="input-group">
                    <label><i class="fas fa-lock"></i> Password *</label>
                    <input type="password" name="password" required placeholder="Enter password">
                </div>

                <div class="input-group">
                    <label><i class="fas fa-lock"></i> Confirm Password *</label>
                    <input type="password" name="password_confirmation" required placeholder="Re-enter password">
                </div>
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-check-circle"></i> Create Account
            </button>

            <div class="auth-footer">
                Already have an account? <a href="/login">Sign in here</a>
            </div>
        </form>
    </div>
</div>

<script>
    // Progress bar animation on form interaction
    const form = document.getElementById('registerForm');
    const progressBar = document.querySelector('.progress-bar');
    const inputs = form.querySelectorAll('input[required], select[required]');
    
    function updateProgress() {
        const filled = Array.from(inputs).filter(input => input.value.trim() !== '').length;
        const percentage = (filled / inputs.length) * 100;
        progressBar.style.width = percentage + '%';
    }
    
    inputs.forEach(input => {
        input.addEventListener('input', updateProgress);
        input.addEventListener('change', updateProgress);
    });
    
    // Initial check
    updateProgress();
</script>
@endsection
