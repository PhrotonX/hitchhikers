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

const signupForm = document.getElementById('signup-form');
if (signupForm) {
    signupForm.addEventListener('submit', function(e){
        e.preventDefault();

        if(!validateStep()) return; 

        window.location.href = '/success';
    });
}

showStep(0);
