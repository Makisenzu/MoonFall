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
    <div class="card-header bg-white">
        <h5 class="mb-0">Safe Zone Management</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Zone Name</label>
                    <input type="text" class="form-control" id="zoneName">
                </div>
                <div class="mb-3">
                    <label class="form-label">Zone Type</label>
                    <select class="form-select" id="zoneType">
                        <option value="hospital">Hospital</option>
                        <option value="shelter">Shelter</option>
                        <option value="police">Police Station</option>
                        <option value="evacuation">Evacuation Center</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Coordinates</label>
                    <input type="text" class="form-control" id="zoneCoordinates" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Capacity</label>
                    <input type="number" class="form-control" id="zoneCapacity">
                </div>
            </div>
        </div>
        <button id="saveZone" class="btn btn-primary">Save Safe Zone</button>
    </div>
</div>
@endsection