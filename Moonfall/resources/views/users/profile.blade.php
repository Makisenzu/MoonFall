@extends('layouts.userLayout')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Profile Information</h4>
                    
                    @if (!$applicants->contains('applicant_id', $userData->id))
                        <form action="{{ route('volunteerStore', $userData->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-person-plus"></i> Apply as Volunteer
                            </button>
                            <input type="hidden" name="status" value="Pending">
                        </form>
                    @else
                        @foreach ($applicants->where('applicant_id', $userData->id) as $applicant)
                            @if ($applicant->status == 'Pending')
                                <span class="badge bg-warning">Application Pending</span>
                            @elseif ($applicant->status == 'Approved')
                                <span class="badge bg-success">Approved Volunteer</span>
                            @elseif ($applicant->status == 'Rejected')
                                <span class="badge bg-danger">Application Rejected</span>
                            @endif
                        @endforeach
                    @endif
                </div>
                <div class="card-body">
                    <form action="{{ route('userUpdate', $userData->id) }}" id="profileForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="firstName" class="form-label fw-semibold">Firstname</label>
                                <input type="text" class="form-control form-control-lg" name="name" id="firstName" value="{{ $userData->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="lastName" class="form-label fw-semibold">Lastname</label>
                                <input type="text" class="form-control form-control-lg" name="lastname" value="{{ $userData->lastname }}" id="lastName" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control form-control-lg" name="email" id="email" value="{{ $userData->email }}" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="phoneNumber" class="form-label fw-semibold">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                <input type="tel" class="form-control form-control-lg" name="phone_number" id="phoneNumber" value="{{ $userData->phone_number }}" required>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control form-control-lg" name="password" id="password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="rePassword" class="form-label fw-semibold">Re-enter password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" class="form-control form-control-lg" name="re-pass" id="rePassword">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="picture" class="form-label fw-semibold">Profile Picture</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-image"></i></span>
                                <input type="file" class="form-control form-control-lg" name="picture" id="picture">
                            </div>
                            @if(isset($userData->picture) && $userData->picture)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/'.$userData->picture) }}" alt="Current profile picture" class="img-thumbnail" style="max-height: 100px">
                                </div>
                            @endif
                        </div>
                        
                        <div class="d-flex gap-3">
                            <a href="{{ route('userDashboardCreate') }}" class="btn btn-secondary btn-lg flex-grow-1">Go Back</a>
                            <button type="submit" name="action" value="update" class="btn btn-primary btn-lg flex-grow-1">
                                <i class="bi bi-check-circle"></i> Update Information
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="{{ asset('js/profile.js') }}"></script>