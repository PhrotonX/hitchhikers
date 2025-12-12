@extends('layouts.app')

@push('head')
    @vite(['resources/css/profile.css'])
@endpush

@section('content')
    <div class="profile-container">
        <div class="profile-sidebar">
            <div class="profile-photo">
                <img src="{{ asset('images/placeholder_profile.png') }}" alt="Profile Photo" id="profilePhoto">
                <button class="update-photo-btn" type="button">{{ __('Update Photo') }}</button>
            </div>
            <nav class="profile-nav">
                <button class="nav-item active" data-section="personal" type="button">{{ __('Profile Info') }}</button>
                <button class="nav-item" data-section="security" type="button">{{ __('Security') }}</button>
            </nav>
            <div class="profile-submit-container">
                <button id="submitProfileBtn" class="save-btn" type="button">{{ __('Submit Profile') }}</button>
            </div>
        </div>

        <div class="profile-content">
            <!-- Profile Info Section -->
            <section id="personal" class="profile-section active">
                <h2>{{ __('Profile Information') }}</h2>
                <form id="profileInfoForm" method="POST" action="{{ route('user.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="fullName">{{ __('Full Name') }}</label>
                        <input type="text" id="fullName" name="name" value="{{ Auth::user()->name ?? '' }}" required>
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">{{ __('Email') }}</label>
                        <input type="email" id="email" name="email" value="{{ Auth::user()->email ?? '' }}" required>
                        @error('email')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">{{ __('Phone Number') }}</label>
                        <input type="tel" id="phone" name="phone" value="{{ Auth::user()->phone ?? '' }}" required>
                        @error('phone')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address">{{ __('Address') }}</label>
                        <textarea id="address" name="address" required>{{ Auth::user()->address ?? '' }}</textarea>
                        @error('address')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="save-btn">{{ __('Save Changes') }}</button>
                </form>
            </section>

            <!-- Security Section -->
            <section id="security" class="profile-section">
                <h2>{{ __('Security Settings') }}</h2>
                <form id="securityForm" method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="currentPassword">{{ __('Current Password') }}</label>
                        <input type="password" id="currentPassword" name="current_password" required>
                        @error('current_password')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="newPassword">{{ __('New Password') }}</label>
                        <input type="password" id="newPassword" name="password" required>
                        @error('password')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">{{ __('Confirm New Password') }}</label>
                        <input type="password" id="confirmPassword" name="password_confirmation" required>
                        @error('password_confirmation')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="save-btn">{{ __('Update Password') }}</button>
                </form>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.nav-item').forEach(button => {
            button.addEventListener('click', function() {
                const section = this.dataset.section;
                
                // Remove active class from all nav items and sections
                document.querySelectorAll('.nav-item').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.profile-section').forEach(sec => sec.classList.remove('active'));
                
                // Add active class to clicked nav item and corresponding section
                this.classList.add('active');
                document.getElementById(section).classList.add('active');
            });
        });

        // Handle profile photo update
        document.querySelector('.update-photo-btn').addEventListener('click', function() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.onchange = function(e) {
                const file = e.target.files[0];
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('profilePhoto').src = event.target.result;
                };
                reader.readAsDataURL(file);
            };
            input.click();
        });
    </script>
@endpush
