@extends('layouts.app')

@push('head')
    <style>
        :root {
            --primary: #1E3A8A;
            --primary-light: #3B5FCF;
        }

        body {
            background: linear-gradient(135deg, #1E3A8A, #3B5FCF);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }

        .success-card {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 40px 30px;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 1s ease forwards;
        }

        .success-card h1 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .success-card h3 {
            font-size: 18px;
            font-weight: 400;
            margin-bottom: 20px;
            color: #555;
        }

        .success-card p {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }

        .timer {
            font-weight: 600;
            font-size: 18px;
            color: #2c3e50;
        }

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

        .progress-bar {
            width: 100%;
            height: 6px;
            background-color: #ddd;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 20px;
        }

        .progress-bar-inner {
            height: 100%;
            width: 0%;
            background-color: #4facfe;
            transition: width 1s linear;
        }

        .next-btn {
            background-color: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
            padding: clamp(10px, 2vw, 14px);
            border-radius: 8px;
            font-size: clamp(14px, 2.5vw, 16px);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .next-btn::after {
            content: "";
            position: absolute;
            left: 50%;
            top: 50%;
            width: 0;
            height: 0;
            background: rgba(0, 0, 0, 0.05);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.3s ease, height 0.3s ease;
        }

        .next-btn:hover::after {
            width: 200%;
            height: 500%;
        }

        .next-btn:hover {
            background-color: var(--primary-light);
            color: white;
            border-color: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
@endpush

@section('content')
    <div class="success-card">
        <h1>{{ __('You are now Registered!') }}</h1>
        <h3>{{ __('Welcome to the Hitchhike community!') }}</h3>
        <p>{{ __('Thank you for signing up. You will be redirected to the homepage in') }} <span class="timer" id="countdown">15</span> {{ __('seconds.') }}</p>
        <div class="progress-bar">
            <div class="progress-bar-inner" id="progress-bar-inner"></div>
        </div><br>
        <button type="button" class="next-btn" onclick="goNext()">{{ __('Go to Homepage now') }}</button>
    </div>
@endsection

@push('scripts')
    <script>
        let countdown = 15;
        const countdownEl = document.getElementById('countdown');
        const progressBar = document.getElementById('progress-bar-inner');

        const interval = setInterval(() => {
            countdown--;
            if (countdown >= 0) {
                countdownEl.textContent = countdown;
                progressBar.style.width = ((15 - countdown) / 15 * 100) + '%';
            }
            if (countdown <= 0) {
                clearInterval(interval);
                document.querySelector('.success-card').style.transition = "opacity 1s ease";
                document.querySelector('.success-card').style.opacity = 0;
                setTimeout(() => {
                    goNext();
                }, 1000);
            }
        }, 1000);

        function goNext() {
            document.body.style.transition = "opacity 0.8s ease";
            document.body.style.opacity = 0;

            setTimeout(() => {
                window.location.href = "{{ route('dashboard') }}";
            }, 800);
        }
    </script>
@endpush
