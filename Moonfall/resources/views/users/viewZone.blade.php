@extends('layouts.userLayout')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-lg-8">
            <h1 class="mb-2">Explore Zones</h1>
            <p class="text-muted">Find and navigate to zones from your current location</p>
        </div>
        <div class="col-lg-4 d-flex justify-content-lg-end align-items-center">
            <button id="currentLocationBtn" class="btn btn-outline-primary me-2">
                <i class="bi bi-geo-alt-fill me-1"></i> Use My Location
            </button>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                    <li><h6 class="dropdown-header">Zone Type</h6></li>
                    <li>
                        <div class="dropdown-item">
                            <div class="form-check">
                                <input class="form-check-input filter-check" type="checkbox" value="Residential" id="filterResidential" checked>
                                <label class="form-check-label" for="filterResidential">Residential</label>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown-item">
                            <div class="form-check">
                                <input class="form-check-input filter-check" type="checkbox" value="Commercial" id="filterCommercial" checked>
                                <label class="form-check-label" for="filterCommercial">Commercial</label>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown-item">
                            <div class="form-check">
                                <input class="form-check-input filter-check" type="checkbox" value="Industrial" id="filterIndustrial" checked>
                                <label class="form-check-label" for="filterIndustrial">Industrial</label>
                            </div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><h6 class="dropdown-header">Distance</h6></li>
                    <li>
                        <div class="dropdown-item px-3">
                            <label for="radiusSlider" class="form-label d-flex justify-content-between">
                                <span>Radius: </span>
                                <span id="radiusValue">10 km</span>
                            </label>
                            <input type="range" class="form-range" min="1" max="50" step="1" id="radiusSlider" value="10">
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><button class="dropdown-item text-primary" id="resetFilters">Reset All Filters</button></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">Zone Map</h5>
                    <div>
                        <button id="toggleRoutesBtn" class="btn btn-sm btn-outline-secondary me-2">
                            <i class="bi bi-map me-1"></i> Toggle Routes
                        </button>
                        {{-- <button id="fullscreenMapBtn" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrows-fullscreen"></i>
                        </button> --}}
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
                        {{-- <button type="button" class="btn btn-outline-secondary active" id="listViewBtn">
                            <i class="bi bi-list-ul"></i>
                        </button> --}}
                        {{-- <button type="button" class="btn btn-outline-secondary" id="gridViewBtn">
                            <i class="bi bi-grid"></i>
                        </button> --}}
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <script src="{{ asset('js/user.js') }}" defer></script>
</div>
@endsection