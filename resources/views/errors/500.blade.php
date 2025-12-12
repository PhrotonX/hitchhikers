@extends('layouts.app')

@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #007bff;
            --white: #ffffff;
            --text-dark: #333;
            --text-light: #555;
        }
        .error-page-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 60vh; 
            padding: 40px 20px;
            background-color: var(--white);
        }
        .error-page-container .error-code {
            font-size: 6rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin: 0;
        }
        .error-page-container .error-title {
            font-size: 2rem;
            font-weight: 500;
            color: var(--text-dark);
            margin-top: 10px;
        }
        .error-page-container .error-message {
            font-size: 1.1rem;
            color: var(--text-light);
            margin-top: 15px;
            max-width: 400px;
        }
        .btn-primary {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 24px;
            background-color: var(--primary-blue);
            color: var(--white);
            text-decoration: none;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover { 
            background-color: #0056b3; 
        }
    </style>
@endpush

@section('content')
    <div class="error-page-container">
        <div class="error-code">500</div>
        <h1 class="error-title">Internal Server Error</h1>
        <p class="error-message">
            Oops! Something went wrong on our end. 
            Our team has been notified and we're working to fix it. Please try again later.
        </p>
        <button onclick="history.back()" class="btn-primary">Go Back</button>
        <a href="{{ route('home') }}" class="btn-primary" style="margin-left: 10px;">Go to Homepage</a>
    </div>
@endsection
