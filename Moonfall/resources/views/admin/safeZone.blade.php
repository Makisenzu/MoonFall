@extends('layouts.adminLayout')
@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Safe Zone Map</h5>
    </div>
    <div class="card-body p-0">
        <div id="safeZoneMap" style="height: 500px; width: 100%;"></div>
    </div>
</div>

<div class="card shadow-sm">
    <form id="safeZoneForm" method="POST">
        @csrf
        <div class="card-header bg-white">
            <h5 class="mb-0">Safe Zone Management</h5>
        </div>
        <div class="card-body">
            <div id="formMessages"></div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Zone Name</label>
                        <input type="text" class="form-control" name="location_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Zone Type</label>
                        <select class="form-select" name="occupation" required>
                            <option value="Hospital">Hospital</option>
                            <option value="Police">Police Station</option>
                            <option value="Evacuation">Evacuation Center</option>
                            <option value="Danger">Danger Zone</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Latitude</label>
                        <input type="number" step="0.00000001" class="form-control" id="latitude" name="latitude" readonly required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Longitude</label>
                        <input type="number" step="0.00000001" class="form-control" id="longitude" name="longitude" readonly required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Radius (meters)</label>
                        <input type="number" class="form-control" name="radius" min="100" max="30000" value="500" required>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save Safe Zone</button>
        </div>
    </form>
</div>
@endsection