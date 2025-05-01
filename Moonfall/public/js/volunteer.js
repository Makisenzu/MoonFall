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
        icon: 'user',
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
            const lat = e.lngLat.lat;
            
            map.flyTo({
                center: [lng, lat],
                zoom: 15
            });
        });
        
        if (typeof window.shelters !== 'undefined' && window.shelters.length > 0) {
            console.log('Adding shelters:', window.shelters);
            addShelters(window.shelters);
            
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
            icon.className = 'fa fa-user';
            icon.style.color = 'white';
            icon.style.fontSize = '24px';
            el.appendChild(icon);
            
            const users = shelter.user;
            const popupContent = ` 
            <div style="max-width: 200px">
                <h4 style="margin: 0 0 5px 0">${shelter.user.name} ${shelter.user.lastname}</h4>
                <div><strong>Email:</strong> ${shelter.user.email}</div>
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



function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            document.getElementById('latitudeInput').value = lat;
            document.getElementById('longitudeInput').value = lng;
            
            if (clickMarker) {
                clickMarker.remove();
            }

            const popup = new mapboxgl.Popup()
                .setHTML('Current Location<br>' + lat.toFixed(6) + ', ' + lng.toFixed(6))
                .addTo(map);
            
            clickMarker = new mapboxgl.Marker({
                color: '#FFA500'
            })
                .setLngLat([lng, lat])
                .setPopup(popup)
                .addTo(map);
            
            map.flyTo({
                center: [lng, lat],
                zoom: 16
            });
        }, function(error) {
            alert("Error getting location: " + error.message);
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}


document.addEventListener('DOMContentLoaded', function () {
    initMap();
});