let map;
let clickMarker = null;
const animatedCircles = [];

const shelterIcons = {
    'Food': {
        icon: 'bowl-food',
        markerColor: '#00FF00',
        circleColor: 'rgba(0, 255, 0, 0.2)'
    },
    'Hospital': {
        icon: 'hospital',
        markerColor: '#0000FF', 
        circleColor: 'rgba(0, 0, 255, 0.2)'
    },
    'Evacuation': {
        icon: 'home',
        markerColor: '#FFD700',
        circleColor: 'rgba(255, 215, 0, 0.2)'
    },
    'Danger': {
        icon: 'warning',
        markerColor: '#FF0000',
        circleColor: 'rgba(255, 0, 0, 0.2)'
    },
    'Police': {
        icon: 'shield',
        markerColor: '#000000',
        circleColor: 'rgba(0, 0, 0, 0.2)'
    }
};


function createPulsingCircle(lat, lng, radiusInMeters, color) {
    const id = 'circle-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
    const circleLayerId = id + '-layer';
    const circleSourceId = id + '-source';
    
    function getRadiusInPixels(radiusMeters, zoom) {
        return radiusMeters / (0.075 * Math.pow(2, 20 - zoom));
    }
    
    const initialZoom = map.getZoom();
    let currentRadius = getRadiusInPixels(radiusInMeters, initialZoom);
    
    map.addSource(circleSourceId, {
        type: 'geojson',
        data: {
            type: 'Feature',
            geometry: {
                type: 'Point',
                coordinates: [lng, lat]
            }
        }
    });
    
    map.addLayer({
        id: circleLayerId,
        type: 'circle',
        source: circleSourceId,
        paint: {
            'circle-radius': currentRadius,
            'circle-color': color,
            'circle-opacity': 0.2,
            'circle-stroke-color': color.replace('0.2)', '1)'),
            'circle-stroke-width': 1
        }
    });
    
    function updateRadius() {
        const zoom = map.getZoom();
        currentRadius = getRadiusInPixels(radiusInMeters, zoom);
        map.setPaintProperty(circleLayerId, 'circle-radius', currentRadius);
    }
    
    map.on('zoom', updateRadius);
    
    let currentScale = 1;
    let growing = false;
    const animationInterval = setInterval(() => {
        if (growing) {
            currentScale += 0.02;
            if (currentScale >= 1.2) growing = false;
        } else {
            currentScale -= 0.02;
            if (currentScale <= 1.0) growing = true;
        }
        
        map.setPaintProperty(circleLayerId, 'circle-radius', currentRadius * currentScale);
    }, 100);
    
    return {
        id: id,
        layerId: circleLayerId,
        sourceId: circleSourceId,
        stopPulsing: () => {
            clearInterval(animationInterval);
            map.off('zoom', updateRadius);
            if (map.getLayer(circleLayerId)) map.removeLayer(circleLayerId);
            if (map.getSource(circleSourceId)) map.removeSource(circleSourceId);
        }
    };
}

function initMap() {
    const apiKey = window.APP_CONFIG?.apiKey;
    mapboxgl.accessToken = apiKey;
    map = new mapboxgl.Map({
        container: 'allMap',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: [125.97666490, 8.50410607],
        zoom: 15,
    });
    
    map.addControl(new mapboxgl.NavigationControl());
    map.on('load', () => {
        console.log('Map loaded');

        map.on('click', function(e) {
            const lng = e.lngLat.lng;
            const lat = e.lngLat.lat
            document.getElementById('latitudeInput').value = lat
            document.getElementById('longitudeInput').value = lng
            map.flyTo({
                center: [lng, lat],
                zoom: 15
            });
        });
        
        if (typeof window.shelters !== 'undefined' && window.shelters.length > 0) {
            console.log('Adding shelters:', window.shelters);
            addShelters(window.shelters);
            
            const firstShelter = window.shelters[0];
            flyToShelter(firstShelter.latitude, firstShelter.longitude);
        }
    });
}

function addShelters(shelters) {
    animatedCircles.forEach(circle => circle.stopPulsing());
    animatedCircles.length = 0;
    
    shelters.forEach(shelter => {
        try {
            const iconConfig = shelterIcons[shelter.occupation] || shelterIcons['Evacuation'];
            
            console.log('Adding shelter:', shelter, 'IconConfig:', iconConfig);
            
            const pulsingCircle = createPulsingCircle(
                parseFloat(shelter.latitude),
                parseFloat(shelter.longitude),
                parseFloat(shelter.radius),
                iconConfig.circleColor
            );
            
            animatedCircles.push(pulsingCircle);
            
            const el = document.createElement('div');
            el.className = 'map-marker';
            el.style.backgroundColor = iconConfig.markerColor;
            el.style.width = '54px';
            el.style.height = '54px';
            el.style.borderRadius = '50%';
            el.style.border = '2px solid white';
            el.style.display = 'flex';
            el.style.justifyContent = 'center';
            el.style.alignItems = 'center';
            el.style.cursor = 'pointer';

            const icon = document.createElement('i');
            icon.className = 'fa fa-' + iconConfig.icon;
            icon.style.color = 'white';
            icon.style.fontSize = '24px';
            el.appendChild(icon);
            
            const popupContent = ` 
                <div style="max-width: 200px">
                    <h4 style="margin: 0 0 5px 0">${shelter.location_name}</h4>
                    <div><strong>Type:</strong> ${shelter.occupation}</div>
                    <div><strong>Radius:</strong> ${shelter.radius}m</div>
                </div>
            `;
            
            new mapboxgl.Marker(el)
                .setLngLat([parseFloat(shelter.longitude), parseFloat(shelter.latitude)])
                .setPopup(new mapboxgl.Popup({ offset: 25 }).setHTML(popupContent))
                .addTo(map);
                
        } catch (error) {
            console.error('Error adding shelter:', shelter, error);
        }
    });
}



function flyToShelter(lat, lng) {
    map.flyTo({
        center: [lng, lat],
        zoom: 16
    });
}
document.getElementById('routeButton').addEventListener('click', () => {
    const latFrom = parseFloat(document.getElementById('LatitudeFrom').value);
    const longFrom = parseFloat(document.getElementById('LongitudeFrom').value);
    const latTo = parseFloat(document.getElementById('latitudeInput').value);
    const longTo = parseFloat(document.getElementById('longitudeInput').value);

    if (isNaN(latFrom) || isNaN(longFrom) || isNaN(latTo) || isNaN(longTo)) {
        alert('Please ensure both origin and destination coordinates are set.');
        return;
    }

    const url = `https://api.mapbox.com/directions/v5/mapbox/walking/${longFrom},${latFrom};${longTo},${latTo}?geometries=geojson&access_token=${mapboxgl.accessToken}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            const route = data.routes[0].geometry;
            
            if (map.getSource('route')) {
                map.removeLayer('route');
                map.removeSource('route');
            }

            map.addSource('route', {
                type: 'geojson',
                data: {
                    type: 'Feature',
                    geometry: route
                }
            });

            map.addLayer({
                id: 'route',
                type: 'line',
                source: 'route',
                layout: {
                    'line-join': 'round',
                    'line-cap': 'round'
                },
                paint: {
                    'line-color': '#ff0000',
                    'line-width': 6,
                    'line-opacity': 1
                }
            });

            map.fitBounds([
                [longFrom, latFrom],
                [longTo, latTo]
            ], { padding: 40 });

        })
        .catch(error => {
            console.error('Error fetching route:', error);
            alert('Failed to get route.');
        });
});
function currentLocation()
{
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            position => {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;

                document.getElementById('LatitudeFrom').value = userLat
                document.getElementById('LongitudeFrom').value = userLng

                const userMarkerEl = document.createElement('div');
                userMarkerEl.className = 'map-marker';
                userMarkerEl.style.backgroundColor = '#FFD700';
                userMarkerEl.style.width = '36px';
                userMarkerEl.style.height = '36px';
                userMarkerEl.style.borderRadius = '50%';
                userMarkerEl.style.border = '2px solid white';
                userMarkerEl.style.display = 'flex';
                userMarkerEl.style.justifyContent = 'center';
                userMarkerEl.style.alignItems = 'center';
                userMarkerEl.style.cursor = 'pointer';

                const icon = document.createElement('i');
                icon.className = 'fa fa-location-arrow';
                icon.style.color = 'white';
                icon.style.fontSize = '18px';
                userMarkerEl.appendChild(icon);

                new mapboxgl.Marker(userMarkerEl)
                    .setLngLat([userLng, userLat])
                    .setPopup(new mapboxgl.Popup({ offset: 25 }).setHTML('<strong>Your Location</strong>'))
                    .addTo(map);

                createPulsingCircle(userLat, userLng, 40, 'rgba(255, 215, 0, 0.3)');

                flyToLocation(userLat, userLng);
            },
            error => {
                alert('Unable to retrieve your location.');
                console.error(error);
            }
        );
    } else {
        alert('Geolocation is not supported by your browser.');
    }
}
document.addEventListener('DOMContentLoaded', function() {
    currentLocation();
    initMap();
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
    if (!window.Echo) {
        console.error('Echo is not initialized!');
        return;
    }
    const connection = window.Echo.connector.pusher.connection;

    connection.bind('state_change', (state) => {
        console.log('Connection state:', state.current);
    });
    console.log(window.Echo);
    
    connection.bind('connected', () => {
        console.log('✅ Fully connected to Reverb server');
    });
    const newsAudience = window.userRole;
    const channel = window.Echo.channel(`news.alert.${newsAudience}`);
    channel.listen('.news.alert', (data)=>{
        console.log('📢 NEWS EVENT RECEIVED:', data);
        setTimeout(() => document.body.style.border = '', 10000);


        showNewsNotification(data);
        addNewsToDashboard(data);
    })
    .error((err)=>{
        console.error('Channel error:', err);
    });
    window.testChannel = () => {
        channel.whisper('test', { time: new Date().toISOString() });
    };

    function showNewsNotification(news) {
        const urgencyClass = news.urgency === 'high' ? 'blue-urgent' : 
                            news.urgency === 'medium' ? 'blue-warning' : 'blue-normal';
        
        const content = `
            <strong>${news.news_name}</strong>
            <p>${news.description}</p>
            <span class="badge ${urgencyClass}">${news.urgency}</span>
            <small>${new Date(news.created_at).toLocaleString()}</small>
        `;
        
        const title = (newsAudience === 'volunteer') ? 'Volunteer Update' : 'News Update';

        showNotification(title, content, urgencyClass);        
    }
    function showNotification(title, content, type = 'blue-normal') {
        if (!document.getElementById('blue-notification-styles')) {
            const styles = document.createElement('style');
            styles.id = 'blue-notification-styles';
            styles.textContent = `
                .notification {
                    position: relative;
                    padding: 15px;
                    margin-bottom: 10px;
                    border-radius: 6px;
                    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
                    animation: slideIn 0.3s ease-out;
                    border-left: 5px solid #4a90e2;
                    background-color: #f0f8ff;
                }
                
                .blue-urgent {
                    background-color: #d4e5ff;
                    border-left-color: #0052cc;
                    color: #004099;
                }
                
                .blue-warning {
                    background-color: #e6f2ff;
                    border-left-color: #2e86de;
                    color: #1a5186;
                }
                
                .blue-normal {
                    background-color: #f0f8ff;
                    border-left-color: #4a90e2;
                    color: #2c5282;
                }
                
                .notification-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 8px;
                }
                
                .notification-header h4 {
                    margin: 0;
                    font-size: 16px;
                    font-weight: 600;
                    color: #2c5282;
                }
                
                .notification-body {
                    font-size: 14px;
                }
                
                .notification .close {
                    background: none;
                    border: none;
                    color: #4a5568;
                    font-size: 20px;
                    cursor: pointer;
                    padding: 0 5px;
                }
                
                .notification .badge {
                    display: inline-block;
                    padding: 3px 8px;
                    border-radius: 12px;
                    font-size: 12px;
                    font-weight: 600;
                    margin-top: 8px;
                }
                
                .badge.blue-urgent {
                    background-color: #0052cc;
                    color: white;
                }
                
                .badge.blue-warning {
                    background-color: #2e86de;
                    color: white;
                }
                
                .badge.blue-normal {
                    background-color: #4a90e2;
                    color: white;
                }
                
                @keyframes slideIn {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                
                /* Create notifications container if it doesn't exist */
                #notifications-container {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    max-width: 350px;
                    z-index: 9999;
                }
            `;
            document.head.appendChild(styles);
        }
        
        if (!document.getElementById('notifications-container')) {
            const container = document.createElement('div');
            container.id = 'notifications-container';
            document.body.appendChild(container);
        }
        
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="notification-header">
                <h4>${title}</h4>
                <button class="close">&times;</button>
            </div>
            <div class="notification-body">${content}</div>
        `;
        
        notification.querySelector('.close').addEventListener('click', () => {
            notification.classList.add('fade-out');
            setTimeout(() => notification.remove(), 300);
        });
        
        const container = document.getElementById('notifications-container');
        container.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => notification.remove(), 300);
        }, 10000);
    }
});