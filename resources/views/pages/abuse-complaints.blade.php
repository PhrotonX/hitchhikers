@extends('layouts.app')

@push('head')
    <style>
        .report-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .report-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .report-header h1 {
            font-size: 2rem;
            color: var(--text-dark, #333);
            margin-bottom: 0.5rem;
        }

        .report-header p {
            color: #666;
            font-size: 1.1rem;
        }

        .alert-info {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 2rem;
            color: #004085;
        }

        .report-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark, #333);
        }

        .form-group .required {
            color: #dc3545;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary, #007bff);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .form-hint {
            font-size: 0.85rem;
            color: #999;
            margin-top: 0.25rem;
        }

        .btn-primary {
            display: inline-block;
            padding: 0.75rem 2rem;
            background: var(--primary, #007bff);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            text-align: center;
        }

        .btn-primary:hover {
            background: var(--primary-dark, #0056b3);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            margin-top: 1rem;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .severity-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 0.75rem;
        }

        .severity-option {
            padding: 1rem;
            border: 2px solid #ddd;
            border-radius: 6px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .severity-option:hover {
            border-color: var(--primary, #007bff);
            background: #f8f9fa;
        }

        .severity-option input {
            display: none;
        }

        .severity-option input:checked + .label {
            color: var(--primary, #007bff);
            font-weight: 600;
        }

        .severity-option input:checked {
            border-color: var(--primary, #007bff);
        }

        @media (max-width: 768px) {
            .report-header h1 {
                font-size: 1.5rem;
            }

            .report-card {
                padding: 1.5rem;
            }

            .severity-options {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
@endpush

@section('content')
    <div class="report-container">
        <div class="report-header">
            <h1>{{ __('Report an Abuse or Issue') }}</h1>
            <p>{{ __('Help us maintain a safe community by reporting inappropriate behavior') }}</p>
        </div>

        <div class="alert-info">
            <strong>{{ __('Important:') }}</strong> {{ __('Your report is confidential and will be reviewed by our safety team. False reports may result in account suspension.') }}
        </div>

        <div class="report-card">
            <form method="POST" action="{{ route('abuse.report') ?? '#' }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="reportType">{{ __('Report Type') }} <span class="required">*</span></label>
                    <select id="reportType" name="type" class="form-control" required>
                        <option value="">{{ __('Select a type') }}</option>
                        <option value="abusive_behavior">{{ __('Abusive Behavior') }}</option>
                        <option value="harassment">{{ __('Harassment') }}</option>
                        <option value="unsafe_driving">{{ __('Unsafe Driving') }}</option>
                        <option value="fraud">{{ __('Fraud/Scam') }}</option>
                        <option value="discrimination">{{ __('Discrimination') }}</option>
                        <option value="other">{{ __('Other') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="severity">{{ __('Severity') }} <span class="required">*</span></label>
                    <select id="severity" name="severity" class="form-control" required>
                        <option value="">{{ __('Select severity level') }}</option>
                        <option value="low">{{ __('Low') }}</option>
                        <option value="medium">{{ __('Medium') }}</option>
                        <option value="high">{{ __('High') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="userReported">{{ __('User Being Reported (if applicable)') }}</label>
                    <input type="text" id="userReported" name="user_reported" class="form-control" placeholder="{{ __('Username or ID') }}">
                    <div class="form-hint">{{ __('Leave blank if reporting content or system issue') }}</div>
                </div>

                <div class="form-group">
                    <label for="rideId">{{ __('Related Ride (if applicable)') }}</label>
                    <input type="text" id="rideId" name="ride_id" class="form-control" placeholder="{{ __('Ride ID') }}">
                    <div class="form-hint">{{ __('Enter the ride ID if this is related to a specific ride') }}</div>
                </div>

                <div class="form-group">
                    <label for="description">{{ __('Detailed Description') }} <span class="required">*</span></label>
                    <textarea id="description" name="description" class="form-control" placeholder="{{ __('Please provide a detailed account of what happened...') }}" required></textarea>
                    <div class="form-hint">{{ __('Be as specific as possible to help our team investigate') }}</div>
                </div>

                <div class="form-group">
                    <label for="evidence">{{ __('Evidence or Screenshots (optional)') }}</label>
                    <input type="file" id="evidence" name="evidence[]" class="form-control" accept="image/*" multiple>
                    <div class="form-hint">{{ __('Upload up to 5 images as evidence') }}</div>
                </div>

                <div class="form-group">
                    <label for="email">{{ __('Contact Email') }} <span class="required">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ Auth::user()->email ?? '' }}" required>
                    <div class="form-hint">{{ __('We\'ll use this to follow up on your report') }}</div>
                </div>

                <div class="form-group">
                    <input type="checkbox" id="followUp" name="follow_up" checked>
                    <label for="followUp" style="display: inline; margin-left: 0.5rem;">{{ __('I want to receive updates about this report') }}</label>
                </div>

                <button type="submit" class="btn-primary">{{ __('Submit Report') }}</button>
                <button type="button" class="btn-primary btn-secondary" onclick="window.history.back()">{{ __('Cancel') }}</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('reportType').addEventListener('change', function() {
            console.log('Report type selected:', this.value);
        });
    </script>
@endpush
