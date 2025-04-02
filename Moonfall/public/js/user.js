document.addEventListener('DOMContentLoaded', function() {
    const map = L.map('mapContainer').setView([8.50449271, 125.97699206], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    const zoneLayers = [];
    let userLocationMarker = null;
    let userLocation = null;
    let routingControl = null;

    function getZoneColor(occupation) {
        switch(occupation) {
            case 'Danger': return '#ff3030';
            case 'Hospital': return '#ee82ee';
            case 'Evacuation': return '#98fb98';
            case 'Police': return '#91a3b0';
            default: return '#30a2ff';
        }
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

    function addZoneToMap(zone) {
        const coords = [zone.latitude, zone.longitude];
        const zoneColor = getZoneColor(zone.occupation);

        const circle = L.circle(coords, {
            radius: zone.radius,
            color: zoneColor,
            fillColor: zoneColor,
            fillOpacity: 0.3,
            weight: 2
        }).addTo(map);

        const marker = L.marker(coords, {
            icon: createMarkerIcon(zoneColor)
        }).addTo(map)
            .bindPopup(`
                <div class="zone-popup">
                    <h5>${zone.location_name}</h5>
                    <p><strong>Type:</strong> ${zone.occupation}</p>
                    <p><strong>Radius:</strong> ${zone.radius}m</p>
                    ${userLocation ? `
                    <button class="btn btn-sm btn-primary navigate-btn" 
                            data-lat="${zone.latitude}" 
                            data-lng="${zone.longitude}">
                        <i class="bi bi-signpost"></i> Get Directions
                    </button>
                    ` : ''}
                </div>
            `);
        marker.on('popupopen', function() {
            document.querySelector('.navigate-btn')?.addEventListener('click', function() {
                const lat = parseFloat(this.getAttribute('data-lat'));
                const lng = parseFloat(this.getAttribute('data-lng'));
                navigateToZone(lat, lng);
            });
        });
        
        zoneLayers.push({ marker, circle });
    
        createZoneListItem(zone, zoneColor);
    }
    function createZoneListItem(zone, color) {
        const zonesList = document.getElementById('zonesList');
        const listItem = document.createElement('div');
        listItem.className = 'zone-list-item';
        listItem.innerHTML = `
            <div class="zone-header">
                <span class="zone-type" style="background-color: ${color}">${zone.occupation}</span>
                ${userLocation ? `<span class="zone-distance">${calculateDistance(zone)}</span>` : ''}
            </div>
            <h4 class="zone-name">${zone.location_name}</h4>
            <p class="zone-radius">Radius: ${zone.radius}m</p>
            <div class="zone-actions">
                <button class="btn btn-sm btn-outline-primary view-zone" 
                        data-lat="${zone.latitude}" 
                        data-lng="${zone.longitude}">
                    <i class="bi bi-eye"></i> View
                </button>
                ${userLocation ? `
                <button class="btn btn-sm btn-primary navigate-zone" 
                        data-lat="${zone.latitude}" 
                        data-lng="${zone.longitude}">
                    <i class="bi bi-signpost"></i> Navigate
                </button>
                ` : ''}
            </div>
        `;
        zonesList.appendChild(listItem);

        listItem.querySelector('.view-zone').addEventListener('click', function() {
            const lat = parseFloat(this.getAttribute('data-lat'));
            const lng = parseFloat(this.getAttribute('data-lng'));
            map.setView([lat, lng], 15);
        });
        
        if (userLocation) {
            listItem.querySelector('.navigate-zone')?.addEventListener('click', function() {
                const lat = parseFloat(this.getAttribute('data-lat'));
                const lng = parseFloat(this.getAttribute('data-lng'));
                navigateToZone(lat, lng);
            });
        }
    }

    function calculateDistance(zone) {
        if (!userLocation) return '';
        
        const distance = map.distance(
            [userLocation.lat, userLocation.lng],
            [zone.latitude, zone.longitude]
        );
        
        return distance < 1000 ? 
            `${Math.round(distance)}m away` : 
            `${(distance / 1000).toFixed(1)}km away`;
    }

    function navigateToZone(lat, lng) {
        if (!userLocation) {
            alert('Please enable location services to get directions');
            return;
        }
        
        if (routingControl) {
            map.removeControl(routingControl);
        }

        document.getElementById('directionsPanel').style.display = 'block';
        document.getElementById('directionsContent').innerHTML = `
            <div class="text-center py-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Calculating route...</p>
            </div>
        `;
        
        routingControl = L.Routing.control({
            waypoints: [
                L.latLng(userLocation.lat, userLocation.lng),
                L.latLng(lat, lng)
            ],
            routeWhileDragging: false,
            showAlternatives: false,
            addWaypoints: false,
            draggableWaypoints: false,
            fitSelectedRoutes: true,
            lineOptions: {
                styles: [{color: '#3b82f6', opacity: 0.7, weight: 5}]
            },
            createMarker: function() { return null; }
        }).addTo(map);
        
        routingControl.on('routesfound', function(e) {
            const routes = e.routes;
            const directionsContent = document.getElementById('directionsContent');
            directionsContent.innerHTML = '';
            
            if (routes && routes.length > 0) {
                const route = routes[0];

                const summary = document.createElement('div');
                summary.className = 'route-summary';
                summary.innerHTML = `
                    <div class="d-flex justify-content-between">
                        <span><strong>Distance:</strong> ${(route.summary.totalDistance / 1000).toFixed(1)} km</span>
                        <span><strong>Time:</strong> ${Math.round(route.summary.totalTime / 60)} min</span>
                    </div>
                `;
                directionsContent.appendChild(summary);

                const instructions = document.createElement('div');
                instructions.className = 'route-instructions';
                
                route.instructions.forEach((instr, index) => {
                    const step = document.createElement('div');
                    step.className = 'route-step';
                    step.innerHTML = `
                        <div class="step-number">${index + 1}</div>
                        <div class="step-text">${instr.text}</div>
                        <div class="step-distance">${(instr.distance / 1000).toFixed(1)} km</div>
                    `;
                    instructions.appendChild(step);
                });
                
                directionsContent.appendChild(instructions);
            }
        });
        
        routingControl.on('routingerror', function(e) {
            document.getElementById('directionsContent').innerHTML = `
                <div class="alert alert-danger">
                    Could not calculate route: ${e.error.message}
                </div>
            `;
        });
    }

    function loadAllZones() {
        fetch('/zones')
            .then(response => response.json())
            .then(zones => {
                zoneLayers.forEach(layer => {
                    map.removeLayer(layer.marker);
                    map.removeLayer(layer.circle);
                });
                zoneLayers.length = 0;
                document.getElementById('zonesList').innerHTML = '';
                
                zones.forEach(zone => {
                    addZoneToMap(zone);
                });

                document.getElementById('zonesCount').textContent = `Showing ${zones.length} zones`;
            })
            .catch(error => {
                console.error('Error loading zones:', error);
                document.getElementById('zonesList').innerHTML = `
                    <div class="alert alert-danger">
                        Failed to load zones. Please try again later.
                    </div>
                `;
            });
    }

    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                position => {
                    userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    
                    map.setView([userLocation.lat, userLocation.lng], 13);
                    
                    if (userLocationMarker) {
                        userLocationMarker.setLatLng([userLocation.lat, userLocation.lng]);
                    } else {
                        userLocationMarker = L.marker([userLocation.lat, userLocation.lng], {
                            icon: L.divIcon({
                                className: 'user-location-marker',
                                html: '<i class="bi bi-geo-alt-fill"></i>',
                                iconSize: [30, 30]
                            })
                        }).addTo(map)
                        .bindPopup('Your current location');
                    }
                    loadAllZones();
                },
                error => {
                    console.error('Error getting location:', error);
                    alert('Could not get your location. Please ensure location services are enabled.');
                }
            );
        } else {
            alert('Geolocation is not supported by your browser.');
        }
    }
    document.getElementById('currentLocationBtn').addEventListener('click', getCurrentLocation);
    document.getElementById('closeDirectionsBtn').addEventListener('click', function() {
        document.getElementById('directionsPanel').style.display = 'none';
        if (routingControl) {
            map.removeControl(routingControl);
            routingControl = null;
        }
    });
    loadAllZones();
});