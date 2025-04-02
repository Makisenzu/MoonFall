@extends('layouts.loginLayout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="register-container">
    <div class="register-card" id="registerForm" role="form" style="display: none">
        <h2 style="text-align: center"><strong>Create Account</strong></h2>
        <form id="registrationForm" action="" method="POST">
            @csrf
            <div class="name-group">
                <div class="name-field">
                    <strong>First Name <strong id="dot">*</strong></strong>
                    <input type="text" name="name">
                    @error('name')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="name-field">
                    <strong>Last Name <strong id="dot">*</strong></strong>
                    <input type="text" name="lastname">
                    @error('lastname')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <strong>Email <strong id="dot">*</strong></strong>
            <input type="email" name="email">
            @error('email')
            <span class="text-danger">{{ $message }}</span>
            @enderror
            
            <strong>Password <strong id="dot">*</strong></strong>
            <input type="password" name="password">
            @error('password')
            <span class="text-danger">{{ $message }}</span>
            @enderror

            <strong>Mobile <strong id="dot">*</strong></strong>
            <div class="phone-input-container">
                <input type="text" id="countryCodeInput" name="country_code" readonly>
                <input type="text" id="phone_number" name="phone_number">
                @error('phone_number')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <strong>Birthday <strong id="dot">*</strong></strong>
            <input type="date" name="birthday">
            @error('birthday')
            <span class="text-danger">{{ $message }}</span>
            @enderror
            <div class="form-group">
                <strong>Gender <strong id="dot">*</strong></strong>
                <div style="display: flex; flex-direction: row; align-items: center; gap: 10px; white-space: nowrap;">
                    <input type="radio" name="gender" value="male" id="male"><label for="male"> Male</label>
                    <input type="radio" name="gender" value="female" id="female"><label for="female"> Female</label>
                    <input type="radio" name="gender" value="other" id="other"><label for="other"> Prefer not to say</label>
                </div>
                @error('gender')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">
            <div>
                <a id="cancelBtn" class="btn" href="javascript:void(0);" onclick="showLogin()">CANCEL</a>
                <button class="btn" id="createBtn" type="submit">Create Account</button>
            </div>
        </form>
        <div id="message"></div>
    </div>

    <div class="register-card" id="loginForm" role="form">
        <h2 style="text-align: center; font-size: 45px"><strong>LOGIN</strong></h2>
        <form action="" method="POST">
            @csrf
            <strong>Email <strong id="dot">*</strong></strong>
            <input type="email" name="email" value="">
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
    
            <strong>Password <strong id="dot">*</strong></strong>
            <input type="password" name="password">
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
    
            <button id="signIn-btn" type="submit">Sign In</button>
        </form>
        <strong>
            <a style="text-align: center; text-decoration:underline" class="nav-link" href="javascript:void(0);" onclick="showCreate()">Create an account?</a>
        </strong>
    </div>
</div>
<script src="{{ asset('js/login.js') }}"></script>
@endsection