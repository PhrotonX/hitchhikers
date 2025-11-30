@extends('layouts.app')

@push('head')
    @vite(['resources/css/signup.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endpush

@section('content')
<header class="main-header">
    <div class="header-logo">
        <img src="{{ Vite::asset('resources/img/Hitchhike Logo.png') }}" alt="Logo">
        <span>Hitchhike</span>
    </div>

    <nav class="header-nav">
        <span class="separator">|</span> 
        <a href="{{ url('/') }}">Home</a>
        <a href="{{ url('/about') }}">About</a>
        <a href="{{ route('login') }}">Login</a>
    </nav>

    <a href="{{ route('register') }}" class="header-btn">Get Started</a>
</header>

<body class="auth-page">
    <div class="container">
        <div class="signup-card">
            <div class="signup-header">
                <h1>Sign Up</h1>
                <p>Join the Hitchhike community today</p>
            </div>

            <div class="progress-container">
                <div class="progress-bar" id="progress-bar"></div>
            </div>

            <form id="signup-form" method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="form-section step" id="step-role">
                    <h3>Choose Registration Type</h3>
                    <div class="button-group">
                        <button type="button" class="signup-btn" onclick="startRegistration('passenger')">
                            <span>Passenger</span><br>
                            <img src="{{ Vite::asset('resources/img/Passenger Icon Black.png') }}" alt="Passenger Icon" class="btn-icon">
                            <img src="{{ Vite::asset('resources/img/Passenger Icon White.png') }}" alt="Passenger Icon" class="btn-icon hover">
                        </button>

                        <button type="button" class="signup-btn" onclick="startRegistration('driver')">
                            <span>Driver</span><br>
                            <img src="{{ Vite::asset('resources/img/Driver Icon Black.png') }}" alt="Driver Icon" class="btn-icon">
                            <img src="{{ Vite::asset('resources/img/Driver Icon White.png') }}" alt="Driver Icon" class="btn-icon hover">
                        </button>
                    </div>
                </div>

                <div class="form-section step" id="step-personal">
                    <h2>Step 1: Personal Information</h2>
                    <div class="form-grid">
                        <div class="input-group required">
                            <label for="firstName">First Name</label>
                            <input type="text" id="firstName" name="first_name" required>
                        </div>
                        <div class="input-group required">
                            <label for="lastName">Last Name</label>
                            <input type="text" id="lastName" name="last_name" required>
                        </div>
                        <div class="input-group required">
                            <label for="age">Age</label>
                            <input type="number" id="age" name="age" required>
                        </div>
                        <div class="input-group required">
                            <label for="phone">Mobile Number</label>
                            <input type="tel" id="phone" name="phone" required>
                        </div>
                    </div>
                    <div class="button-group">
                        <button type="button" class="back-btn" onclick="prevStep()">Back</button>
                        <button type="button" class="signup-btn" onclick="nextStep()">Next</button>
                    </div>
                </div>

                <div class="form-section step" id="step-account">
                    <h2>Step 2: Create Account</h2>
                    <div class="form-grid">
                        <div class="input-group required">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="input-group required">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <div class="input-group" id="repassword-group" required>
                            <label for="confirm-password">Re-enter Password</label>
                            <input type="password" id="confirm-password" name="password_confirmation">
                        </div>
                    </div>
                    <div class="button-group">
                        <button type="button" class="back-btn" onclick="prevStep()">Back</button>
                        <button type="button" class="signup-btn" onclick="nextStep()">Next</button>
                    </div>
                </div>

                <div class="form-section step" id="step-id">
                    <h2>Step 3: ID Validation</h2>
                    <div class="form-grid">
                        <div class="input-group required">
                            <label for="licenseNumber">Driver's License Number</label>
                            <input type="text" id="licenseNumber" name="license_number">
                        </div>
                        <div class="input-group required">
                            <label for="licenseExpiry">License Expiry Date</label>
                            <input type="date" id="licenseExpiry" name="license_expiry">
                        </div>
                        <div class="input-group required">
                            <label>Upload License (Front)</label>
                            <input type="file" name="license_front" accept="image/*">
                        </div>
                        <div class="input-group required">
                            <label>Upload License (Back)</label>
                            <input type="file" name="license_back" accept="image/*">
                        </div>
                        <div class="input-group required">
                            <label>Upload Government ID</label>
                            <input type="file" name="government_id" accept="image/*">
                        </div>
                    </div>
                    <div class="button-group">
                        <button type="button" class="back-btn" onclick="prevStep()">Back</button>
                        <button type="button" class="signup-btn" onclick="nextStep()">Next</button>
                    </div>
                </div>

                <div class="form-section step" id="step-vehicle">
                    <h2>Step 4: Vehicle Information</h2>
                    <div class="form-grid">
                        <div class="input-group required">
                            <label for="vehicleType">Vehicle Type</label>
                            <input type="text" id="vehicleType" name="vehicle_type">
                        </div>
                        <div class="input-group required">
                            <label for="plateNumber">Plate Number</label>
                            <input type="text" id="plateNumber" name="plate_number">
                        </div>
                        <div class="input-group required">
                            <label for="vehicleModel">Vehicle Model</label>
                            <input type="text" id="vehicleModel" name="vehicle_model">
                        </div>
                        <div class="input-group required">
                            <label for="vehicleColor">Vehicle Color</label>
                            <input type="text" id="vehicleColor" name="vehicle_color">
                        </div>
                        <div class="input-group required">
                            <label for="yearModel">Year Model</label>
                            <input type="number" id="yearModel" name="year_model">
                        </div>
                        <div class="input-group required">
                            <label>Upload OR/CR Documents</label>
                            <input type="file" name="or_cr_documents" accept="image/*">
                        </div>
                    </div>
                    <div class="button-group">
                        <button type="button" class="back-btn" onclick="prevStep()">Back</button>
                        <button type="button" class="signup-btn" onclick="nextStep()">Next</button>
                    </div>
                </div>

                <div class="form-section step" id="step-photo">
                    <h2>Upload Profile Picture</h2>
                    <div class="form-grid">
                        <div class="input-group required">
                            <label>Profile Picture</label>
                            <input type="file" name="profile_picture" accept="image/*">
                        </div>
                    </div>
                    <div class="button-group">
                        <button type="button" class="back-btn" onclick="prevStep()">Back</button>
                        <button type="submit" class="signup-btn">Create Account</button>
                    </div>
                </div>
            </form>
            <br>
            <p class="login-link">
                Already have an account? <a href="{{ route('login') }}">Log in</a>
            </p>
        </div>
    </div>

    <script>
        let currentStep = 0;
        let userType = '';
        const steps = ['step-role', 'step-personal', 'step-account', 'step-id', 'step-vehicle', 'step-photo'];

        function startRegistration(type) {
            userType = type;
            currentStep = 1;

            if (userType === 'passenger') {
                const repassword = document.getElementById('repassword-group');
                if (repassword) repassword.remove();
            }

            showStep(currentStep);
        }

        function showStep(step) {
            steps.forEach((id, index) => {
                const el = document.getElementById(id);
                if (!el) return;

                if (userType === 'passenger' && (id === 'step-id' || id === 'step-vehicle')) {
                    el.style.display = 'none';
                    return;
                }

                el.style.display = index === step ? 'block' : 'none';
            });

            updateProgress();
        }

        function nextStep() {
            if (!validateStep()) return;

            currentStep++;

            if (userType === 'passenger' && currentStep === 3) currentStep = 5;

            showStep(currentStep);
        }

        function prevStep() {
            currentStep--;

            if (userType === 'passenger' && currentStep === 4) currentStep = 2;

            showStep(currentStep);
        }

        function updateProgress() {
            const progress = document.getElementById('progress-bar');
            let totalSteps = userType === 'driver' ? 5 : 3;
            let stepIndex = currentStep;

            if (userType === 'passenger' && currentStep > 2) stepIndex = 3;

            progress.style.width = ((stepIndex) / totalSteps * 100) + '%';
        }

        function validateStep() {
            const stepId = steps[currentStep];
            const stepEl = document.getElementById(stepId);
            if (!stepEl) return true;

            const requiredInputs = stepEl.querySelectorAll('input[required], select[required]');
            let valid = true;

            requiredInputs.forEach(input => {
                const group = input.parentElement;
                let errorMsg = group.querySelector('.error-message');

                if (!errorMsg) {
                    errorMsg = document.createElement('span');
                    errorMsg.className = 'error-message';
                    errorMsg.style.color = 'red';
                    errorMsg.style.fontSize = '12px';
                    errorMsg.style.display = 'none';
                    group.appendChild(errorMsg);
                }

                if (!input.value) {
                    group.classList.add('input-error');
                    errorMsg.textContent = 'This field is required';
                    errorMsg.style.display = 'block';
                    valid = false;
                } else {
                    group.classList.remove('input-error');
                    errorMsg.style.display = 'none';
                }
            });

            if (stepId === 'step-account') {
                const password = document.getElementById('password').value;
                const confirmPasswordEl = document.getElementById('confirm-password');
                if (confirmPasswordEl && password !== confirmPasswordEl.value) {
                    const group = confirmPasswordEl.parentElement;
                    let errorMsg = group.querySelector('.error-message');
                    if (!errorMsg) {
                        errorMsg = document.createElement('span');
                        errorMsg.className = 'error-message';
                        errorMsg.style.color = 'red';
                        errorMsg.style.fontSize = '12px';
                        group.appendChild(errorMsg);
                    }
                    group.classList.add('input-error');
                    errorMsg.textContent = 'Passwords do not match';
                    errorMsg.style.display = 'block';
                    valid = false;
                }
            }

            return valid;
        }

        showStep(0);
    </script>
</body>
@endsection
