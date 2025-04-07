@extends('layouts.userLayout')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-lg-8">
            <h1 class="mb-2">Explore Zones</h1>
        </div>
        <div class="col-lg-4 d-flex justify-content-lg-end align-items-center">
            <div class="dropdown">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">Zone Map</h5>
                    <div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="mapContainer" class="map-container">
                        <div id="directionsPanel" class="directions-panel">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Directions</h6>
                                <button id="closeDirectionsBtn" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            <div id="directionsContent" class="directions-content">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="row">
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-6 d-flex justify-content-md-end mt-3 mt-md-0">
                            <div class="map-legend">
                                <span class="legend-item"><i class="bi bi-circle-fill text-residential me-1"></i> Residential</span>
                                <span class="legend-item"><i class="bi bi-circle-fill text-commercial me-1"></i> Commercial</span>
                                <span class="legend-item"><i class="bi bi-circle-fill text-industrial me-1"></i> Industrial</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">Nearby Zones</h5>
                    <div class="btn-group btn-group-sm" role="group">
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="zonesList" class="list-view">
                        <div class="zones-loading text-center py-5">
                            <div class="spinner-border text-primary mb-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p>Loading zones...</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white text-center">
                    <span id="zonesCount" class="text-muted">Showing 0 zones</span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="zoneDetailsModal" tabindex="-1" aria-labelledby="zoneDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="zoneDetailsModalLabel">Zone Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div id="zoneDetailMap" class="zone-detail-map mb-3"></div>
                            <div class="d-grid gap-2">
                                <button id="getDirectionsBtn" class="btn btn-primary">
                                    <i class="bi bi-signpost-2 me-2"></i>Get Directions
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4 id="modalZoneName">Zone Name</h4>
                            <p id="modalZoneType" class="badge bg-primary mb-3">Zone Type</p>
                            
                            <h6>Details</h6>
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <th width="40%">Coverage Area</th>
                                        <td id="modalZoneArea">-</td>
                                    </tr>
                                    <tr>
                                        <th>Distance from you</th>
                                        <td id="modalZoneDistance">-</td>
                                    </tr>
                                    <tr>
                                        <th>Estimated time</th>
                                        <td id="modalZoneTime">-</td>
                                    </tr>
                                    <tr>
                                        <th>Coordinates</th>
                                        <td id="modalZoneCoordinates" class="text-muted small">-</td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <div id="routeOptions" class="mt-3">
                                <h6>Route Options</h6>
                                <div class="btn-group w-100 mb-3" role="group">
                                    <input type="radio" class="btn-check" name="travelMode" id="drivingMode" value="DRIVING" checked>
                                    <label class="btn btn-outline-secondary" for="drivingMode">
                                        <i class="bi bi-car-front me-1"></i> Driving
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="travelMode" id="walkingMode" value="WALKING">
                                    <label class="btn btn-outline-secondary" for="walkingMode">
                                        <i class="bi bi-person-walking me-1"></i> Walking
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="travelMode" id="transitMode" value="TRANSIT">
                                    <label class="btn btn-outline-secondary" for="transitMode">
                                        <i class="bi bi-bus-front me-1"></i> Transit
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="showRouteBtn">Show Route</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="locationPermissionModal" tabindex="-1" aria-labelledby="locationPermissionModalLabel" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="locationPermissionModalLabel">Location Access</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-4">
                        <i class="bi bi-geo-alt text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5>Allow Location Access</h5>
                    <p>To show routes from your location to zones, we need permission to access your current location. Please allow location access when prompted.</p>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        If you've previously denied permission, you may need to update your browser settings.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Not Now</button>
                    <button type="button" class="btn btn-primary" id="requestLocationBtn">Allow Access</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loadingRouteModal" tabindex="-1" aria-labelledby="loadingRouteModalLabel" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mb-0">Calculating the best route...</p>
                </div>
            </div>
        </div>
    </div>

    <link href="{{ asset('css/user.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.css' rel='stylesheet' />
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.9.1/mapbox-gl.js'></script>
    <script>
        window.APP_CONFIG = {
            apiKey: "{{ env('MAPBOX_API_KEY') }}"
        };
    </script>
    <script src="{{ asset('js/user.js') }}" defer></script>
</div>
@endsection