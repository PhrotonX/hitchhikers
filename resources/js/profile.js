document.addEventListener('DOMContentLoaded', function() {
    const navItems = document.querySelectorAll('.nav-item');
    const sections = document.querySelectorAll('.profile-section');

    navItems.forEach(item => {
        item.addEventListener('click', () => {
            navItems.forEach(nav => nav.classList.remove('active'));
            sections.forEach(section => section.classList.remove('active'));

            item.classList.add('active');
            const sectionId = item.dataset.section;
            document.getElementById(sectionId).classList.add('active');
        });
    });

    const photoInput = document.createElement('input');
    photoInput.type = 'file';
    photoInput.accept = 'image/*';
    
    const updatePhotoBtn = document.querySelector('.update-photo-btn');
    const profilePhoto = document.getElementById('profilePhoto');

    if (updatePhotoBtn && profilePhoto) {
        updatePhotoBtn.addEventListener('click', () => {
            photoInput.click();
        });

        photoInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profilePhoto.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const formObject = {};
            formData.forEach((value, key) => {
                formObject[key] = value;
            });

            console.log('Form submitted:', formObject);

            alert('Changes saved successfully!');
        });
    });

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

            alert('Password updated successfully!');
            this.reset();
        });
    }

    const submitProfileBtn = document.getElementById('submitProfileBtn');
    if (submitProfileBtn) {
        submitProfileBtn.addEventListener('click', () => {
            const activeForm = document.querySelector('.profile-section.active form');
            if (activeForm) {
                if (typeof activeForm.requestSubmit === 'function') activeForm.requestSubmit();
                else activeForm.submit();
            } else {
                alert('No active section with a form to submit.');
            }
        });
    }
});
