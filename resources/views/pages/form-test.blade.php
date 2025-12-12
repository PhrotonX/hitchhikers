@extends('layouts.app')

@push('head')
    <style>
        .form-container {
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            max-width: 400px;
            margin: 3rem auto;
        }

        .form-container h2 {
            color: var(--text-dark, #333);
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-group {
            width: 100%;
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary, #007bff);
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        .form-group.has-error input,
        .form-group.has-error select,
        .form-group.has-error textarea {
            border-color: var(--primary, #007bff);
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        }

        .form-error-message {
            display: none;
            font-size: 0.9rem;
            color: var(--primary, #007bff);
            margin-top: 5px;
        }

        .form-group.has-error .form-error-message {
            display: block;
        }

        .btn-primary {
            display: inline-block;
            padding: 12px 24px;
            background-color: var(--primary, #007bff);
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark, #0056b3);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .success-message {
            display: none;
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .success-message.show {
            display: block;
        }
    </style>
@endpush

@section('content')
    <div class="form-container">
        <form id="my-form">
            <h2>{{ __('Test Form') }}</h2>

            <div class="success-message" id="success-message">
                {{ __('Form submitted successfully!') }}
            </div>

            <div class="form-group" id="username-group">
                <label for="username">{{ __('Username') }}</label>
                <input type="text" id="username" placeholder="{{ __('Enter username') }}">
                <span class="form-error-message">{{ __('This field is required.') }}</span>
            </div>

            <div class="form-group" id="email-group">
                <label for="email">{{ __('Email') }}</label>
                <input type="email" id="email" placeholder="{{ __('Enter email') }}">
                <span class="form-error-message">{{ __('Please enter a valid email.') }}</span>
            </div>

            <div class="form-group" id="message-group">
                <label for="message">{{ __('Message') }}</label>
                <textarea id="message" placeholder="{{ __('Enter your message') }}" rows="4"></textarea>
                <span class="form-error-message">{{ __('This field is required.') }}</span>
            </div>

            <button type="submit" class="btn-primary">{{ __('Submit') }}</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const form = document.getElementById('my-form');
        const usernameInput = document.getElementById('username');
        const emailInput = document.getElementById('email');
        const messageInput = document.getElementById('message');
        const usernameGroup = document.getElementById('username-group');
        const emailGroup = document.getElementById('email-group');
        const messageGroup = document.getElementById('message-group');
        const successMessage = document.getElementById('success-message');

        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        form.addEventListener('submit', (e) => {
            e.preventDefault();

            let hasErrors = false;

            // Check username
            if (usernameInput.value.trim() === '') {
                usernameGroup.classList.add('has-error');
                hasErrors = true;
            } else {
                usernameGroup.classList.remove('has-error');
            }

            // Check email
            if (emailInput.value.trim() === '' || !validateEmail(emailInput.value)) {
                emailGroup.classList.add('has-error');
                hasErrors = true;
            } else {
                emailGroup.classList.remove('has-error');
            }

            // Check message
            if (messageInput.value.trim() === '') {
                messageGroup.classList.add('has-error');
                hasErrors = true;
            } else {
                messageGroup.classList.remove('has-error');
            }

            if (!hasErrors) {
                successMessage.classList.add('show');
                console.log('Form submitted successfully!');
                
                // Hide success message after 3 seconds
                setTimeout(() => {
                    successMessage.classList.remove('show');
                }, 3000);

                // Optional: reset form
                form.reset();
            }
        });

        // Clear error on input
        [usernameInput, emailInput, messageInput].forEach(input => {
            input.addEventListener('input', function() {
                const group = this.closest('.form-group');
                if (this.value.trim() !== '') {
                    group.classList.remove('has-error');
                }
            });
        });
    </script>
@endpush
