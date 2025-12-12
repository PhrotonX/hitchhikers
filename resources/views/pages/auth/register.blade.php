@props([
    'gender' => [
        'male' => __('gender.male'),
        'female' => __('gender.female'),
    ],
])

@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1E3A8A;
            --primary-light: #3B5FCF;
            --background-hover: #F9FAFB;
            --text-dark: #1F2937;
            --text-light: #6B7280;
            --border: #E5E7EB;
            --card-bg: white;
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --error: #EF4444;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        .auth-page {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            max-width: 900px;
        }

        .signup-card {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(4px);
            border-radius: 16px;
            padding: 40px;
            box-shadow: var(--shadow);
            width: 100%;
            border: 1px solid var(--border);
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
            width: 0%;
            background-color: var(--primary);
            transition: width 0.3s ease;
            border-radius: 8px;
        }

        .form-section {
            margin-bottom: 24px;
        }

        .form-section h2 {
            font-size: 20px;
            color: var(--text-dark);
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 6px;
            color: var(--text-dark);
            font-size: 14px;
            font-weight: 500;
        }

        .input-group.required label::after {
            content: " *";
            color: var(--error);
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

        .button-group {
            display: flex;
            gap: 16px;
            margin-top: 32px;
            justify-content: center;
        }

        .button-group .back-btn,
        .button-group .next-btn,
        .button-group .submit-btn {
            flex-grow: 1;
            max-width: 200px;
        }

        .next-btn, .submit-btn {
            background-color: var(--primary);
            color: white;
            border: 2px solid var(--primary);
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .next-btn:hover, .submit-btn:hover {
            background-color: var(--primary-light);
            border-color: var(--primary-light);
            transform: translateY(-2px);
        }

        .back-btn {
            background-color: transparent;
            border: 2px solid var(--border);
            color: var(--text-light);
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .hidden {
            display: none !important;
        }

        .login-link {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: var(--text-light);
        }

        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
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

        @media (max-width: 600px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .button-group {
                flex-direction: column;
            }
            .button-group .back-btn,
            .button-group .next-btn,
            .button-group .submit-btn {
                max-width: 100%;
            }
        }
    </style>
@endpush

@section('content')
<div class="auth-page" style="background-image: url('{{ Vite::asset('resources/img/Background Img.png') }}');">
    <div class="container">
        <div class="signup-card">
            <div class="signup-header">
                <h1><i class="fas fa-user-plus"></i> Create Account</h1>
                <p>Join Hitchhike and start your journey</p>
            </div>

            <div class="progress-container">
                <div class="progress-bar" id="progressBar"></div>
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

            <form action="/register" method="POST" id="registrationForm">
                @csrf

                <!-- Step 1: Basic Information -->
                <div class="form-section step" id="step-1">
                    <h2>Basic Information</h2>
                    <div class="form-grid">
                        <div class="input-group required">
                            <label for="first_name"><i class="fas fa-user"></i> First Name</label>
                            <input type="text" id="first_name" name="first_name" required value="{{ old('first_name') }}">
                        </div>
                        <div class="input-group">
                            <label for="middle_name"><i class="fas fa-user"></i> Middle Name</label>
                            <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
                        </div>
                        <div class="input-group required">
                            <label for="last_name"><i class="fas fa-user"></i> Last Name</label>
                            <input type="text" id="last_name" name="last_name" required value="{{ old('last_name') }}">
                        </div>
                        <div class="input-group">
                            <label for="ext_name"><i class="fas fa-user"></i> Extension Name</label>
                            <input type="text" id="ext_name" name="ext_name" value="{{ old('ext_name') }}" placeholder="Jr., Sr., III, etc.">
                        </div>
                    </div>
                    <div class="button-group">
                        <button type="button" class="next-btn" onclick="nextStep()">Next</button>
                    </div>
                </div>

                <!-- Step 2: Personal Details -->
                <div class="form-section step hidden" id="step-2">
                    <h2>Personal Details</h2>
                    <div class="form-grid">
                        <div class="input-group required">
                            <label for="gender"><i class="fas fa-venus-mars"></i> Gender</label>
                            <select id="gender" name="gender" required>
                                <option value="">Select gender</option>
                                @foreach($gender as $key => $value)
                                    <option value="{{ $key }}" {{ old('gender') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group required">
                            <label for="birthdate"><i class="fas fa-calendar"></i> Birthdate</label>
                            <input type="date" id="birthdate" name="birthdate" required value="{{ old('birthdate') }}">
                        </div>
                        <div class="input-group required">
                            <label for="phone"><i class="fas fa-phone"></i> Phone Number</label>
                            <input type="tel" id="phone" name="phone" required value="{{ old('phone') }}" placeholder="+63 912 345 6789">
                        </div>
                    </div>
                    <div class="button-group">
                        <button type="button" class="back-btn" onclick="prevStep()">Back</button>
                        <button type="button" class="next-btn" onclick="nextStep()">Next</button>
                    </div>
                </div>

                <!-- Step 3: Account Setup -->
                <div class="form-section step hidden" id="step-3">
                    <h2>Account Setup</h2>
                    <div class="form-grid">
                        <div class="input-group required">
                            <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                            <input type="email" id="email" name="email" required value="{{ old('email') }}" placeholder="your.email@example.com">
                        </div>
                        <div class="input-group required">
                            <label for="password"><i class="fas fa-lock"></i> Password</label>
                            <input type="password" id="password" name="password" required placeholder="Enter password">
                        </div>
                        <div class="input-group required">
                            <label for="password_confirmation"><i class="fas fa-lock"></i> Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Re-enter password">
                        </div>
                    </div>
                    <div class="button-group">
                        <button type="button" class="back-btn" onclick="prevStep()">Back</button>
                        <button type="submit" class="submit-btn"><i class="fas fa-check-circle"></i> Create Account</button>
                    </div>
                </div>
            </form>

            <p class="login-link">
                Already have an account? <a href="/login">Sign in here</a>
            </p>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;
    const totalSteps = 3;

    function showStep(step) {
        document.querySelectorAll('.step').forEach(s => s.classList.add('hidden'));
        document.getElementById(`step-${step}`).classList.remove('hidden');
        
        const progress = ((step - 1) / (totalSteps - 1)) * 100;
        document.getElementById('progressBar').style.width = progress + '%';
    }

    function nextStep() {
        const currentStepEl = document.getElementById(`step-${currentStep}`);
        const inputs = currentStepEl.querySelectorAll('input[required], select[required]');
        let valid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                valid = false;
                input.style.borderColor = 'var(--error)';
            } else {
                input.style.borderColor = 'var(--border)';
            }
        });

        if (!valid) {
            alert('Please fill in all required fields');
            return;
        }

        if (currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        showStep(1);
    });
</script>
@endsection
