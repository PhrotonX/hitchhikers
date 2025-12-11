@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* CSS Variables - Defined for design consistency, updated to a professional Indigo scheme */
        :root {
            --primary: #1E3A8A; /* Deep Indigo - Primary brand color */
            --primary-light: #3B5FCF; /* Lighter Indigo - Hover state */
            --background-hover: #F9FAFB; /* Very light Indigo/Lavender for subtle backgrounds */
            --text-dark: #1F2937; /* Very dark gray for headings */
            --text-light: #6B7280; /* Medium gray for body text */
            --border: #E5E7EB; /* Light gray border */
            --card-bg: white; /* Clean white card background */
            --shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --error: #EF4444;
        }

        body {
            background: var(--background);
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden; 
            opacity: 1;
            transition: opacity 0.8s ease;
        }
        /* Auth Page Layout */
        .auth-page {
            /* Using a placeholder image for the background */
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

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 90vw;
        }

        /* Signup Card */
        .signup-card {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(4px);
            border-radius: 16px;
            padding: clamp(24px, 5vw, 40px);
            box-shadow: var(--shadow);
            width: 80vw;
            border: 1px solid var(--border);
            animation: fadeInUp 0.5s ease forwards;
        }

        .signup-header {
            text-align: center;
            margin-bottom: clamp(24px, 5vw, 32px);
        }

        .signup-header h1 {
            font-size: clamp(24px, 4vw, 32px);
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 8px;
        }

        .signup-header p {
            color: var(--text-light);
            font-size: clamp(14px, 2vw, 16px);
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
            width: 0%;
            background-color: var(--primary);
            transition: width 0.3s ease;
            border-radius: 8px;
        }

        /* Form Sections and Grid */
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
            width: 100%;
        }
        
        /* Input Styles */
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 6px;
            color: var(--text-dark);
            font-size: 14px;
            font-weight: 500;
        }

        .input-group input, 
        .input-group select {
            width: 90%;
            margin: auto;
            padding: 12px;
            border: 1px solid var(--border);
            background-color: white;
            border-radius: 8px;
            color: var(--text-dark);
            font-size: 16px;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .input-group input:focus,
        .input-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15); /* Primary color shadow */
        }

        .input-group.required label::after {
            content: " *";
            color: var(--error);
            margin-left: 2px;
        }

        /* Button Styles */
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
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3); /* Primary color shadow */
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
            background-color: var(--background-hover);
        }

        /* Role Selection Buttons */
        .role-btn-group {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 20px;
        }

        .signup-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 150px;
            height: 150px;
            background-color: white;
            border: 2px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .signup-btn i {
            font-size: 40px;
            margin-top: 10px;
            color: var(--primary);
            transition: color 0.3s ease;
        }
        
        .signup-btn:hover {
            border-color: var(--primary);
            background-color: var(--primary);
            color: white;
            transform: scale(1.05);
        }
        
        .signup-btn:hover i {
            color: white;
        }
        
        .signup-btn.selected {
            border-color: var(--primary);
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
        }
        
        .signup-btn.selected i {
            color: white;
        }
        
        /* Utility */
        .hidden {
            display: none !important;
        }

        /* Login Link */
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
            transition: all 0.2s ease;
        }

        .login-link a:hover {
            color: var(--primary-light);
            text-decoration: underline;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mobile Responsiveness */
        @media (max-width: 600px) {
            .signup-card {
                padding: 20px;
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
            .button-group {
                flex-direction: column;
                gap: 10px;
            }
            .button-group .back-btn, 
            .button-group .next-btn,
            .button-group .submit-btn {
                max-width: 100%;
            }
            .role-btn-group {
                flex-direction: column;
            }
            .signup-btn {
                width: 100%;
                height: 80px;
                flex-direction: row;
                justify-content: flex-start;
                gap: 15px;
            }
            .signup-btn i {
                margin-top: 0;
            }
        }
        
        /* Message Box */
        #messageBox {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: var(--primary);
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            opacity: 0;
            transform: translateY(-20px);
            transition: opacity 0.4s ease, transform 0.4s ease;
        }
        #messageBox.show {
            opacity: 1;
            transform: translateY(0);
        }
        .error-message {
            background-color: var(--error) !important;
        }
    </style>
@endpush


@props([
    'gender' => [
        'male' => __('gender.male'),
        'female' => __('gender.female'),
    ],
])


@section('content')
<div class="auth-page" style="background-image: url('{{ Vite::asset('resources/img/Background%20Img.png') }}');">
    <div class="container">
        <div class="signup-card">
            <div class="signup-header">
                <h1 id="form-title">Sign Up</h1>
                <p id="form-subtitle">Join the Hitchhike community today</p>
            </div>

            <!-- Progress bar -->
            <div class="progress-container">
                <div class="progress-bar" id="progressBar"></div>
            </div>

            <form id="registrationForm" onsubmit="return false;">
                <!-- Hidden Field for Role -->
                <input type="hidden" name="role" id="roleInput">

                <!-- Role Selection -->
                <div class="form-section step" id="step-role">
                    <h3>Choose Registration Type</h3>
                    <div class="role-btn-group">
                        <button type="button" class="signup-btn" onclick="startRegistration('passenger')">
                            <span>Passenger</span><br>
                            <img src="{{ Vite::asset('resources/img/Passenger-Icon-Black.png') }}" alt="Passenger Icon" class="btn-icon">
                            <img src="Images/Passenger Icon White.png" alt="Passenger Icon" class="btn-icon hover">
                        </button>

                        <button type="button" class="signup-btn" onclick="startRegistration('driver')">
                            <span>Driver</span><br>
                            <img src="{{asset ('resources/img/Driver-Icon-Black.png') }}" alt="Driver Icon" class="btn-icon">
                            <img src="Images/Driver Icon White.png" alt="Driver Icon" class="btn-icon hover">
                        </button>
                    </div>
                </div>

                <!-- Step 1: Personal Information -->
                <div class="form-section step hidden" id="step-personal">
                    <h2>Personal Information</h2>
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
                        <button type="button" class="next-btn" onclick="nextStep()">Next</button>
                    </div>
                </div>

                <!-- Step 2: Account Creation -->
                <div class="form-section step hidden" id="step-account">
                    <h2>Create Account</h2>
                    <div class="form-grid">
                        <div class="input-group required">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="input-group required">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <div class="input-group required">
                            <label for="confirm-password">Re-enter Password</label>
                            <input type="password" id="confirm-password" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="button-group">
                        <button type="button" class="back-btn" onclick="prevStep()">Back</button>
                        <button type="button" class="next-btn" onclick="nextStep()">Next</button>
                    </div>
                </div>

                <!-- Step 3: ID Validation (Driver Only) -->
                <div class="form-section step hidden" id="step-id">
                    <h2>ID Validation</h2>
                    <div class="form-grid">
                        <div class="input-group required">
                            <label for="licenseNumber">Driver’s License Number</label>
                            <input type="text" id="licenseNumber" name="license_number">
                        </div>
                        <div class="input-group required">
                            <label for="licenseExpiry">License Expiry Date</label>
                            <input type="date" id="licenseExpiry" name="license_expiry">
                        </div>
                        <div class="input-group required">
                            <label>Upload License (Front)</label>
                            <input type="file" accept="image/*" name="license_front">
                        </div>
                        <div class="input-group required">
                            <label>Upload License (Back)</label>
                            <input type="file" accept="image/*" name="license_back">
                        </div>
                        <div class="input-group required">
                            <label>Upload Government ID</label>
                            <input type="file" accept="image/*" name="gov_id">
                        </div>
                    </div>
                    <div class="button-group">
                        <button type="button" class="back-btn" onclick="prevStep()">Back</button>
                        <button type="button" class="next-btn" onclick="nextStep()">Next</button>
                    </div>
                </div>

                <!-- Step 4: Vehicle Information (Driver Only) -->
                <div class="form-section step hidden" id="step-vehicle">
                    <h2>Vehicle Information</h2>
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
                            <input type="file" accept="image/*" name="or_cr">
                        </div>
                    </div>
                    <div class="button-group">
                        <button type="button" class="back-btn" onclick="prevStep()">Back</button>
                        <button type="button" class="next-btn" onclick="nextStep()">Next</button>
                    </div>
                </div>

                <!-- Step 5: Profile Photo (All) -->
                <div class="form-section step hidden" id="step-photo">
                    <h2>Upload Profile Picture</h2>
                    <div class="form-grid">
                        <div class="input-group required">
                            <label>Profile Picture</label>
                            <input type="file" accept="image/*" name="profile_picture">
                        </div>
                    </div>
                    <div class="button-group">
                        <button type="button" class="back-btn" onclick="prevStep()">Back</button>
                        <button type="submit" class="submit-btn" onclick="submitRegistration()" id="submitBtn">
                            <span id="submitText">Create Account</span>
                            <i id="loadingSpinner" class="fas fa-spinner fa-spin hidden"></i>
                        </button>
                    </div>
                </div>
            </form>

            <p id="error-display" class="login-link" style="color: var(--error); font-weight: 600; display: none;"></p>

            <br>
            <p class="login-link">
                Already have an account? <a href='/login'>Log in</a>
            </p>
        </div>
    </div>

    <!-- Custom Message Box -->
    <div id="messageBox" class="hidden" style="display:none;"></div>

    @push('scripts')
    <!-- Firebase Logic -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-app.js";
        import { getAuth, signInAnonymously, signInWithCustomToken, createUserWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-auth.js";
        import { getFirestore, doc, setDoc } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-firestore.js";

        const appId = typeof __app_id !== 'undefined' ? __app_id : 'default-app-id';
        const firebaseConfig = JSON.parse(typeof __firebase_config !== 'undefined' ? __firebase_config : '{}');
        const initialAuthToken = typeof __initial_auth_token !== 'undefined' ? __initial_auth_token : null;
        
        let app, auth, db;
        let currentStepIndex = 0;
        let currentFlow = [];

        // Define step IDs for flows
        const stepsPassenger = ['step-role', 'step-personal', 'step-account', 'step-photo'];
        const stepsDriver = ['step-role', 'step-personal', 'step-account', 'step-id', 'step-vehicle', 'step-photo'];

        // Expose functions to window
        window.startRegistration = startRegistration;
        window.nextStep = nextStep;
        window.prevStep = prevStep;
        window.submitRegistration = submitRegistration;

        function showCustomMessage(message, isError = false) {
            const box = document.getElementById('messageBox');
            box.textContent = message;
            box.className = isError ? 'error-message' : '';
            box.style.display = 'block';
            box.classList.add('show');
            setTimeout(() => { box.style.display = 'none'; }, 3000);
        }

        function updateUI() {
            // Hide all steps
            document.querySelectorAll('.form-section').forEach(el => el.classList.add('hidden'));
            
            // Show current step
            const currentStepId = currentFlow[currentStepIndex];
            const currentElement = document.getElementById(currentStepId);
            if(currentElement) currentElement.classList.remove('hidden');

            // Update Progress Bar
            const progress = ((currentStepIndex) / (currentFlow.length - 1)) * 100;
            document.getElementById('progressBar').style.width = `${progress}%`;
        }

        function startRegistration(role) {
            document.getElementById('roleInput').value = role;
            
            // Update selection UI
            document.querySelectorAll('.signup-btn').forEach(btn => btn.classList.remove('selected'));
            // Note: In this implementation we click directly to start, so we just set flow and go
            
            currentFlow = (role === 'passenger') ? stepsPassenger : stepsDriver;
            currentStepIndex = 1; // Move directly to Step 1
            updateUI();
        }

        function nextStep() {
            // Basic Validation for current step
            const currentStepId = currentFlow[currentStepIndex];
            const currentStepEl = document.getElementById(currentStepId);
            const inputs = currentStepEl.querySelectorAll('input[required], select[required]');
            let valid = true;
            inputs.forEach(input => {
                if(!input.value) valid = false;
            });
            
            if(!valid) {
                showCustomMessage("Please fill in all required fields.", true);
                return;
            }

            if (currentStepIndex < currentFlow.length - 1) {
                currentStepIndex++;
                updateUI();
            }
        }

        function prevStep() {
            if (currentStepIndex > 0) {
                currentStepIndex--;
                updateUI();
            }
        }

        async function initializeFirebase() {
            try {
                app = initializeApp(firebaseConfig);
                auth = getAuth(app);
                db = getFirestore(app);
                if (initialAuthToken) await signInWithCustomToken(auth, initialAuthToken);
                else await signInAnonymously(auth);
            } catch (e) { console.error(e); }
        }

        async function submitRegistration() {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm-password').value;
            
            if(password !== confirm) {
                showCustomMessage("Passwords do not match!", true);
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            document.getElementById('loadingSpinner').classList.remove('hidden');

            try {
                const userCred = await createUserWithEmailAndPassword(auth, email, password);
                const userId = userCred.user.uid;
                
                // Gather all inputs
                const formData = new FormData(document.getElementById('registrationForm'));
                const data = Object.fromEntries(formData.entries());
                data.userId = userId;
                data.createdAt = new Date().toISOString();
                
                // Remove password from stored data
                delete data.password;
                delete data.password_confirmation;

                // Note: File uploads are not handled here (requires Storage), placeholders used.
                
                await setDoc(doc(db, `artifacts/${appId}/users/${userId}/profile`, 'details'), data);
                
                showCustomMessage("Account created successfully!");
                // Redirect logic here
                 setTimeout(() => { window.location.href = "{{ url('login') }}"; }, 1500);

            } catch (error) {
                let msg = error.message;
                if(error.code === 'auth/email-already-in-use') msg = "Email already in use.";
                showCustomMessage(msg, true);
                submitBtn.disabled = false;
                document.getElementById('loadingSpinner').classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            initializeFirebase();
            // Initially show step-role, hide others
            document.querySelectorAll('.form-section').forEach(el => el.classList.add('hidden'));
            document.getElementById('step-role').classList.remove('hidden');
        });
    </script>
    @endpush
    </div>
@endsection