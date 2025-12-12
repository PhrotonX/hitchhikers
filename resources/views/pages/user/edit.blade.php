@props([
    'gender' => [
        'male' => __('gender.male'),
        'female' => __('gender.female'),
    ],
])

@extends('layouts.app')

@section('content')
<x-sidebar-nav />
<div class="main-content">
    <div class="page-header">
        <h1><i class="fas fa-user-edit"></i> Edit Profile</h1>
        <div style="margin-top: 10px;">
            <a href="/user/{{Auth::user()->id}}" class="btn btn-secondary" style="margin-right: 10px;">
                <i class="fas fa-arrow-left"></i> Back to Profile
            </a>
            <a href="/user/{{Auth::user()->id}}/profile-pictures" class="btn btn-primary" style="margin-right: 10px;">
                <i class="fas fa-camera"></i> Manage Profile Picture
            </a>
            <a href="/settings" class="btn btn-primary">
                <i class="fas fa-cog"></i> Settings
            </a>
        </div>
    </div>

    @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
        <div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
            <p style="margin: 0 0 10px 0; color: #856404;">
                <i class="fas fa-exclamation-triangle"></i> {{ __('credentials.email_unverified_msg') }}
            </p>
            <button type="button" class="btn btn-primary" onclick="window.location.href='{{route('verification.send')}}'">
                <i class="fas fa-envelope"></i> {{ __('credentials.email_resend_verification') }}
            </button>
            @if (session('status') === 'verification-link-sent')
                <p style="margin: 10px 0 0 0; color: #28a745;">
                    <i class="fas fa-check-circle"></i> {{ __('credentials.verification_link_sent') }}
                </p>
            @endif
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fas fa-user"></i> Personal Information</h2>
        </div>
        <div class="card-body">
            <form action="/user/{{Auth::user()->id}}/update" method="POST">
                @method('PATCH')
                @csrf
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label><i class="fas fa-user"></i> First Name *</label>
                        <input type="text" name="first_name" required value="{{$user->first_name}}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    </div>
                    <div>
                        <label><i class="fas fa-user"></i> Middle Name</label>
                        <input type="text" name="middle_name" value="{{$user->middle_name}}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label><i class="fas fa-user"></i> Last Name *</label>
                        <input type="text" name="last_name" required value="{{$user->last_name}}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    </div>
                    <div>
                        <label><i class="fas fa-user"></i> Ext. Name</label>
                        <input type="text" name="ext_name" value="{{$user->ext_name}}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label><i class="fas fa-venus-mars"></i> Gender *</label>
                        <select name="gender" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                            @foreach ($gender as $key => $value)
                                @if (old('gender', $user->gender) == $key)
                                    <option value="{{$key}}" selected>{{$value}}</option>
                                @else
                                    <option value="{{$key}}">{{$value}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label><i class="fas fa-calendar"></i> Birthdate *</label>
                        <input type="date" name="birthdate" required value="{{$user->birthdate}}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label><i class="fas fa-envelope"></i> Email *</label>
                        <input type="email" name="email" required value="{{$user->email}}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    </div>
                    <div>
                        <label><i class="fas fa-phone"></i> Phone *</label>
                        <input type="phone" name="phone" required value="{{$user->phone}}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
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

    @include('pages.user.edit-password')
</div>
@endsection