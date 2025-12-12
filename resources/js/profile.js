// Profile page functionality
document.addEventListener('DOMContentLoaded', function() {
    // Navigation functionality
    const navItems = document.querySelectorAll('.nav-item');
    const sections = document.querySelectorAll('.profile-section');

    navItems.forEach(item => {
        item.addEventListener('click', () => {
            // Remove active class from all nav items and sections
            navItems.forEach(nav => nav.classList.remove('active'));
            sections.forEach(section => section.classList.remove('active'));

            // Add active class to clicked nav item and corresponding section
            item.classList.add('active');
            const sectionId = item.dataset.section;
            document.getElementById(sectionId).classList.add('active');
        });
    });

    // Profile photo update functionality
    const photoInput = document.createElement('input');
    photoInput.type = 'file';
    photoInput.accept = 'image/*';
    
    const updatePhotoBtn = document.querySelector('.update-photo-btn');
    const profilePhoto = document.getElementById('profilePhoto');

    updatePhotoBtn.addEventListener('click', () => {
        photoInput.click();
    });

    photoInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profilePhoto.src = e.target.result;
                // Here you would typically upload the file to your server
                // uploadPhotoToServer(this.files[0]);
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Form submission handlers
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const formObject = {};
            formData.forEach((value, key) => {
                formObject[key] = value;
            });

            // Here you would typically send the data to your server
            console.log('Form submitted:', formObject);
            // submitFormToServer(formObject);

            // Show success message
            alert('Changes saved successfully!');
        });
    });

    // Password validation
    const securityForm = document.getElementById('securityForm');
    if (securityForm) {
        securityForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (newPassword !== confirmPassword) {
                alert('New passwords do not match!');
                return;
            }

            // Here you would typically send the password update to your server
            // updatePasswordOnServer(currentPassword, newPassword);

            alert('Password updated successfully!');
            this.reset();
        });
    }

    // Global submit button: submits the currently active section's form
    const submitProfileBtn = document.getElementById('submitProfileBtn');
    if (submitProfileBtn) {
        submitProfileBtn.addEventListener('click', () => {
            const activeForm = document.querySelector('.profile-section.active form');
            if (activeForm) {
                // requestSubmit preferred (fires submit event), fallback to submit()
                if (typeof activeForm.requestSubmit === 'function') activeForm.requestSubmit();
                else activeForm.submit();
            } else {
                alert('No active section with a form to submit.');
            }
        });
    }
});