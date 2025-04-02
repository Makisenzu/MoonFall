@extends('layouts.loginLayout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6" id="registerForm" style="display: none">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-dark text-white">
                    <h3 class="text-center fw-bold my-2">Create Account</h3>
                </div>
                <div class="card-body p-4">
                    <form id="registrationForm" action="{{ route('userStore') }}" method="POST">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-floating mb-3 mb-md-0">
                                    <input class="form-control" id="inputFirstName" type="text" name="name" placeholder="Enter your first name" />
                                    <label for="inputFirstName">First Name <span class="text-danger">*</span></label>
                                    @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input class="form-control" id="inputLastName" type="text" name="lastname" placeholder="Enter your last name" />
                                    <label for="inputLastName">Last Name <span class="text-danger">*</span></label>
                                    @error('lastname')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-floating mb-4">
                            <input class="form-control" id="inputEmail" type="email" name="email" placeholder="name@example.com" />
                            <label for="inputEmail">Email <span class="text-danger">*</span></label>
                            @error('email')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="form-floating mb-4">
                            <input class="form-control" id="inputPassword" type="password" name="password" placeholder="Create a password" />
                            <label for="inputPassword">Password <span class="text-danger">*</span></label>
                            @error('password')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium">Mobile <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="Phone number">
                            </div>
                            @error('phone_number')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium">Birthday <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-calendar"></i></span>
                                <input type="date" class="form-control" name="birthday">
                            </div>
                            @error('birthday')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium">Gender <span class="text-danger">*</span></label>
                            <div class="d-flex flex-wrap gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="male" id="male">
                                    <label class="form-check-label" for="male">Male</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="female" id="female">
                                    <label class="form-check-label" for="female">Female</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="other" id="other">
                                    <label class="form-check-label" for="other">Prefer not to say</label>
                                </div>
                            </div>
                            @error('gender')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                        
                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                            <button class="btn btn-dark" id="createBtn" type="submit">Create Account</button>
                        </div>
                    </form>
                    <div id="message" class="mt-3"></div>
                </div>
            </div>
        </div>

        <div class="col-md-8 col-lg-5" id="loginForm">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-dark text-white">
                    <h3 class="text-center fw-bold my-2">LOGIN</h3>
                </div>
                <div class="card-body p-4">
                    <form action="" method="POST">
                        @csrf
                        <div class="form-floating mb-4">
                            <input class="form-control" id="loginEmail" type="email" name="email" placeholder="name@example.com" />
                            <label for="loginEmail">Email <span class="text-danger">*</span></label>
                            @error('email')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="form-floating mb-4">
                            <input class="form-control" id="loginPassword" type="password" name="password" placeholder="Password" />
                            <label for="loginPassword">Password <span class="text-danger">*</span></label>
                            @error('password')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        
                        <div class="d-grid mb-3">
                            <button class="btn btn-dark btn-lg" id="signIn-btn" type="submit">Sign In</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection