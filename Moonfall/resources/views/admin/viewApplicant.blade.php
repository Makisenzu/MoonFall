@extends('layouts.adminLayout')
@section('content')
<div class="container-fluid py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Volunteer Applications</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle" id="volunteersTable">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" width="80">Picture</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone Number</th>
                            <th scope="col">Status</th>
                            <th scope="col" width="200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applicants as $applicant)
                            <tr data-status="{{ $applicant->status }}">
                                <td>
                                    @if($applicant->user->picture)
                                        <img src="{{ asset('uploads/' . $applicant->user->picture) }}" alt="Profile" class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 50px; height: 50px;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $applicant->user->name }} {{ $applicant->user->lastname }}</strong>
                                </td>
                                <td>
                                    <a style="text-decoration: none; color:black" href="mailto:{{ $applicant->user->email }}">{{ $applicant->user->email }}</a>
                                </td>
                                <td>
                                    <a style="text-decoration: none; color:black" href="tel:{{ $applicant->user->phone_number }}">{{ $applicant->user->phone_number }}</a>
                                </td>
                                <td>
                                    @if($applicant->status == 'Pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($applicant->status == 'Approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($applicant->status == 'Denied')
                                        <span class="badge bg-danger">Denied</span>
                                    @else
                                        <span class="badge bg-danger">Removed</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @if($applicant->status == 'Pending')
                                            <form action="{{ route('approvedApplicant', $applicant->id) }}" method="POST" class="approve-form">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" value="Approved" name="status">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-lg"></i> Approve
                                                </button>
                                            </form>
                                            
                                            <form class="deny-form" action="{{ route('applicationDenied', $applicant->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" value="Denied" name="status">
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-x-lg"></i> Deny
                                                </button>
                                            </form>
                                        @elseif($applicant->status == 'Approved')
                                        <button type="button" disabled class="btn btn-secondary">
                                            <i class="bi bi-eye"></i> Approved
                                        </button>
                                        @else
                                        <button type="button" disabled class="btn btn-secondary">
                                            <i class="bi bi-eye"></i> None
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="alert alert-info mb-0">
                                        <i class="bi bi-info-circle me-2"></i> No volunteer applications found
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="volunteerDetailsModal" tabindex="-1" aria-labelledby="volunteerDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="volunteerDetailsModalLabel">Volunteer Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4" id="volunteerLoader">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading volunteer details...</p>
                </div>
                
                <div id="volunteerDetails" style="display: none;">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <div id="volunteerPicture" class="mb-3"></div>
                            <h5>{{$applicant->user->name}} class="mb-1"></h5>
                            <div id="volunteerStatus" class="mb-3"></div>
                        </div>
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <small class="text-muted">Email</small>
                                        <div id="volunteerEmail" class="fw-bold"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <small class="text-muted">Phone</small>
                                        <div id="volunteerPhone" class="fw-bold"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="border rounded p-3">
                                        <small class="text-muted">Location</small>
                                        <div class="fw-bold">
                                            <span id="volunteerLatitude"></span>, <span id="volunteerLongitude"></span>
                                        </div>
                                        <div id="volunteerMap" class="mt-2" style="height: 200px;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <small class="text-muted">Application Date</small>
                                        <div id="volunteerCreatedAt" class="fw-bold"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <small class="text-muted">Last Updated</small>
                                        <div id="volunteerUpdatedAt" class="fw-bold"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-danger" id="volunteerError" style="display: none;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Error loading volunteer details
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <div id="volunteerModalActions" class="d-flex gap-2"></div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/applicant.js') }}"></script>
@endsection