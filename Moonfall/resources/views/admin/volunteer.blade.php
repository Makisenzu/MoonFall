@extends('layouts.adminLayout')
@section('content')

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-4">Volunteer Management</h1>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">VOLUNTEER APPLICANT</h3>
                </div>
                <div class="card-body">
                    <p class="card-text">Manage new volunteer applications and review candidate information.</p>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Pending Applicants
                            <span class="badge bg-warning rounded-pill">{{ $pending }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Approved
                            <span class="badge bg-success rounded-pill">{{ $approved }}</span>
                        </li>
                    </ul>
                    <div class="d-grid gap-2">
                        <a href="" class="btn btn-outline-primary">View Applications</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title mb-0">EXISTING VOLUNTEER</h3>
                </div>
                <div class="card-body">
                    <p class="card-text">Manage current volunteers, schedule shifts, and track hours.</p>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Active Volunteers
                            <span class="badge bg-success rounded-pill">{{ $volunteerData }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            
                            <span class="badge bg-success rounded-pill"></span>
                        </li>
                    </ul>
                    <div class="d-grid gap-2">
                        <a href="" class="btn btn-outline-success">Volunteer Directory</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-2">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Volunteer Locations</h4>
                </div>
                <div>
                    <div id="volunteer-map" style="height: 400px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Volunteers</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Picture</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td>
                                    <img src="" alt="" width="50px" height="50px">
                                </td>
                                <td>Role</td>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="{{ asset('js/volunteer.js') }}"></script>
@endpush

@endsection