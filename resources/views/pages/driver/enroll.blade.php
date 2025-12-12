@props([
    'driver_type' => [
        'ordinary_driver',
        'company_driver',
        'student_driver',
    ],
])

@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <div class="page-header">
        <h1><i class="fas fa-car-side"></i> {{__('string.enroll_to_driving_program')}}</h1>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-user-plus"></i> Driver Enrollment Form</h2>
        </div>
        <div class="card-body">
            <form action="/driver/enroll/submit" method="POST">
                @csrf
                
                <div style="margin-bottom: 20px;">
                    <label><i class="fas fa-id-card"></i> {{__('credentials.driver_account_name')}} *</label>
                    <input
                        name="driver_account_name"
                        placeholder="{{__('credentials.driver_account_name_hint')}}"
                        value="{{ old('driver_account_name') }}"
                        required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;"
                    />
                    @error('driver_account_name')
                        <span style="color: #dc3545; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label><i class="fas fa-building"></i> {{__('string.company')}}</label>
                    <input
                        name="company"
                        placeholder="{{__('string.company')}}"
                        value="{{ old('company') }}"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;"
                    />
                    @error('company')
                        <span style="color: #dc3545; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label><i class="fas fa-user-tie"></i> {{__('credentials.driver_type')}} *</label>
                    <select name="driver_type" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        @foreach ($driver_type as $value)
                            <option value="{{$value}}" @selected(old('driver_type') == $value)>{{__('credentials.'.$value)}}</option>
                        @endforeach
                    </select>
                    @error('driver_type')
                        <span style="color: #dc3545; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> {{__('string.submit')}}
                </button>
            </form>

            @if($errors->any())
                <div style="background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 6px; padding: 15px; margin-top: 20px;">
                    <strong style="color: #721c24;"><i class="fas fa-exclamation-circle"></i> Errors:</strong>
                    <ul style="margin: 8px 0 0 20px; color: #721c24;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection