@extends('layouts.adminLayout')
@section('content')

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 fw-bold text-primary">Volunteer Management</h1>
            <hr class="my-3">
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Volunteer Locations</h5>
                </div>
                <div class="card-body p-0">
                    <div id="allMap" style="height: 400px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="card h-100 border-0 shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-plus me-2"></i>Volunteer Applications</h5>
                </div>
                <div class="card-body">
                    <div class="row g-0">
                        <div class="col-6 border-end">
                            <div class="text-center p-3">
                                <h3 class="text-warning mb-0">{{ $pending }}</h3>
                                <p class="text-muted mb-0">Pending</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3">
                                <h3 class="text-success mb-0">{{ $approved }}</h3>
                                <p class="text-muted mb-0">Approved</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="{{ route('viewApplicants') }}" class="btn btn-primary w-100">
                        <i class="bi bi-list-check me-2"></i>Manage Applications
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Volunteer Statistics</h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="text-center">
                        <h2 class="mb-3">{{ count($volunteers) }}</h2>
                        <p class="mb-0 text-muted">Active Volunteers</p>
                    </div>
                </div>
                <div class="card-footer bg-white border-0">
                    <button class="btn btn-outline-primary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#volunteerTable">
                        <i class="bi bi-people me-2"></i>View All Volunteers
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="collapse show" id="volunteerTable">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-people me-2"></i>Volunteer Directory</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Profile</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th class="text-end pe-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($volunteers as $volunteer)
                                    <tr>
                                        <td class="ps-3">
                                            @if($volunteer->user->picture)
                                                <img src="{{ asset('uploads/' . $volunteer->user->picture) }}" 
                                                    alt="Profile Picture" 
                                                    width="40" 
                                                    height="40"
                                                    class="rounded-circle">
                                            @else
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                                    style="width: 40px; height: 40px;">
                                                    <i class="bi bi-person text-white"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="fw-medium">{{ $volunteer->user->name }} {{ $volunteer->user->lastname }}</td>
                                        <td><small>{{ $volunteer->user->email }}</small></td>
                                        <td><small>{{ $volunteer->user->phone_number }}</small></td>
                                        <td class="text-end pe-3">
                                            <form class="remove-form" action="{{ route('removeVolunteer', $volunteer->users_id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-eye">Remove as Volunteer</i>
                                                </button>
                                            </form>                                                                                     
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
<script>
    window.APP_CONFIG = {
        apiKey: "{{ env('MAPBOX_API_KEY') }}"
    };
</script>
<script>
    window.shelters = @json($volunteers);
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.remove-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const { isConfirmed } = await Swal.fire({
                title: 'Are you sure?',
                text: "You are about to remove this volunteer!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'Cancel'
            });
    
            if (!isConfirmed) return;
    
            const button = form.querySelector('button[type="submit"]');
            
            try {
                button.disabled = true;
                button.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...`;
                
                const response = await fetch(form.action, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        status: 'Approved'
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    await Swal.fire({
                        title: 'Removed!',
                        text: data.message || 'Applicant has been removed.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                    window.location.reload();
                } else {
                    await Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Something went wrong.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                await Swal.fire({
                    title: 'Error!',
                    text: 'An unexpected error occurred.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            } finally {
                button.disabled = false;
                button.innerHTML = `<i class="bi bi-check-lg"></i> Denied`;
            }
        });
    });
        initMap();
    });
</script>

@endsection