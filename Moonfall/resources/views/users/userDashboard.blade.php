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
                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex flex-wrap">
                                <div class="me-4 mb-2">
                                    <span class="badge bg-success me-1">&nbsp;</span> Evacuation
                                </div>
                                <div class="me-4 mb-2">
                                    <span class="badge bg-primary me-1">&nbsp;</span> Food Zone
                                </div>
                                <div class="me-4 mb-2">
                                    <span class="badge bg-warning me-1">&nbsp;</span> Hospital Zone
                                </div>
                                <div class="me-4 mb-2">
                                    <span class="badge bg-danger me-1">&nbsp;</span> Danger Zone
                                </div>
                                <div class="me-4 mb-2">
                                    <span class="badge bg-secondary me-1">&nbsp;</span> Police Zone
                                </div>
                            </div>
                        </div>
                    </div>
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
    }).addTo(map);
    function getZoneColor(occupation) {
        const colors = {
            'Danger': '#ff3030',
            'Hospital': '#ee82ee',
            'Evacuation': '#98fb98',
            'Police': '#91a3b0',
            'Default': '#30a2ff'
        };
        return colors[occupation] || colors['Default'];
    }
    function createMarkerIcon(color) {
        return L.divIcon({
            className: 'custom-marker',
            html: `<svg viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                     <path fill="${color}" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                   </svg>`,
            iconSize: [24, 24],
            iconAnchor: [12, 24]
        });
    }

    function loadZones() {
        fetch('/zones')
            .then(response => response.json())
            .then(zones => {
                zones.forEach(zone => {
                    const color = getZoneColor(zone.occupation);
                    const marker = L.marker([zone.latitude, zone.longitude], {
                        icon: createMarkerIcon(color)
                    }).addTo(map)
                      .bindPopup(`
                          <b>${zone.location_name}</b><br>
                          Type: ${zone.occupation}<br>
                          ${zone.radius ? 'Radius: ' + zone.radius + 'm' : ''}
                      `);
                    if (zone.radius) {
                        L.circle([zone.latitude, zone.longitude], {
                            radius: zone.radius,
                            color: color,
                            fillColor: color,
                            fillOpacity: 0.2,
                            weight: 2
                        }).addTo(map);
                    }
                });
                if (zones.length > 0) {
                    const bounds = zones.map(zone => [zone.latitude, zone.longitude]);
                    map.fitBounds(bounds, { padding: [50, 50] });
                }
            })
            .catch(error => {
                console.error('Error loading zones:', error);
                alert('Failed to load zones. Please try again later.');
            });
    }
    loadZones();
    document.getElementById('locate-btn').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const { latitude, longitude } = position.coords;
                    map.setView([latitude, longitude], 15);

                    L.marker([latitude, longitude], {
                        icon: createMarkerIcon('#ff00ff')
                    })
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
<style>
    .custom-marker {
        background: transparent;
        border: none;
    }
    #map {
        z-index: 1;
    }
</style>
@endsection