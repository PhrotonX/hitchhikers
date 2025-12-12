@extends('layouts.app')

@push('head')
    <style>
        :root {
            --primary-blue: #007bff;
            --light-blue-bg: #f4f8ff;
            --white: #ffffff;
            --text-dark: #333;
            --text-light: #555;
            --border-blue: #bfe0ff;
        }
        .error-page-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 60vh; 
            padding: 40px 20px;
            box-sizing: border-box; 
        }
        .bg-light-blue { background-color: var(--light-blue-bg); }
        .bg-white { background-color: var(--white); }
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
        .maintenance-icon {
            font-size: 4rem;
            color: var(--primary-blue);
            display: block;
            margin-bottom: 20px;
        }
    </style>
@endpush

@section('content')
    <div class="error-page-container bg-light-blue">
        <span class="maintenance-icon">&#9881;</span>
        <h1 class="error-title">{{ __('Under Maintenance') }}</h1>
        <p class="error-message">
            {{ __('We\'re currently performing scheduled maintenance to improve our service. We\'ll be back online shortly. Thanks for your patience!') }}
        </p>
    </div>
@endsection
