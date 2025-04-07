@extends('layouts.userLayout')
@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title mb-0">Interactive Map</h5>
                </div>
                <div class="card-body">
                    <div id="map" style="height: 400px;" class="w-100 border rounded"></div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <button class="btn btn-sm btn-outline-secondary">
                        <i class="fa fa-location-arrow"></i> Find My Location
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Latest News</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">                       
                        <div class="list-group-item p-3">
                            <div class="row align-items-center">
                                <div class="col-md-9 col-lg-10">
                                    @foreach ($newsData as $data)
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $data->news_name }}</h5>
                                        <small class="text-muted"></small>
                                    </div>
                                    <p class="mb-1">{{ $data->description }}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">Urgency: {{ $data->urgency }}</small>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const map = L.map('map').setView([8.51018945, 125.97101827], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    document.getElementById('locate-btn').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const { latitude, longitude } = position.coords;
                    map.setView([latitude, longitude], 15);

                    L.marker([latitude, longitude])
                        .addTo(map)
                        .bindPopup('Your location')
                        .openPopup();
                },
                function(error) {
                    alert('Error getting your location: ' + error.message);
                }
            );
        } else {
            alert('Geolocation is not supported by your browser');
        }
    });
</script>
@endsection