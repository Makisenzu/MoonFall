@extends('layouts.adminLayout')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Total Users</p>
                        <h4 class="mb-0">{{ $userCount }}</h4>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-users fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <a href="{{ route('adminZoneIndex') }}" style="text-decoration: none;" class="text-muted mb-1">Zones</a>
                        <h4 class="mb-0">{{ $zoneCount }}</h4>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-shield-alt fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Volunteers</p>
                        <h4 class="mb-0">{{ $volunteerCount }}</h4>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-user-friends fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Alerts</p>
                        <h4 class="mb-0">2</h4>
                        <p class="small text-danger mt-2 mb-0">
                            <i class="fas fa-arrow-up me-1"></i>
                            <span>2 new alerts</span>
                        </p>
                    </div>
                    <div class="bg-danger bg-opacity-10 rounded-3 p-3">
                        <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">View Location</h5>
    </div>
    <div class="card-body p-0">
        <div id="allMap" style="height: 500px; width: 100%;"></div>
    </div>
    <div class="card-footer bg-light">
        <div class="d-flex flex-wrap">
            <div class="me-4 mb-2"><span class="badge bg-warning me-1">&nbsp;</span> Evacuation</div>
            <div class="me-4 mb-2"><span class="badge bg-success me-1">&nbsp;</span> Food Zone</div>
            <div class="me-4 mb-2"><span class="badge bg-primary me-1">&nbsp;</span> Hospital Zone</div>
            <div class="me-4 mb-2"><span class="badge bg-danger me-1">&nbsp;</span> Danger Zone</div>
            <div class="me-4 mb-2"><span class="badge bg-dark me-1">&nbsp;</span> Police Zone</div>
        </div>
    </div>
</div>


<link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />
<script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>

<script>
    window.APP_CONFIG = {
        apiKey: "{{ env('MAPBOX_API_KEY') }}"
    };
</script>
<script>
    window.shelters = @json($zoneData);
</script>
<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        initMap();
    });
</script>
@endsection
