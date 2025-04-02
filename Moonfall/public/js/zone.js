document.addEventListener('DOMContentLoaded', function() {
    const map = L.map('safeZoneMap').setView([8.50449271, 125.97699206], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    const zoneLayers = [];
    let tempMarker = null;
    let tempCircle = null;

    const form = document.getElementById('safeZoneForm');
    const messagesDiv = document.getElementById('formMessages');

    function addZoneToMap(zone) {
        const coords = [zone.latitude, zone.longitude];
        let circleColor = '#30a2ff';
        if (zone.occupation === 'Danger') {
            circleColor = '#ff3030';
        }else if (zone.occupation === 'Hospital'){
            circleColor = '#ee82ee';
        }else if (zone.occupation === 'Evacuation'){
            circleColor = '#98fb98';
        }else if (zone.occupation === 'Police'){
            circleColor = '#91a3b0';
        }
        
        const circle = L.circle(coords, {
            radius: zone.radius,
            color: circleColor,
            fillColor: circleColor,
            fillOpacity: 0.3
        }).addTo(map);
        const marker = L.marker(coords).addTo(map)
            .bindPopup(`
                <b>${zone.location_name}</b><br>
                Type: ${zone.occupation}<br>
                Radius: ${zone.radius}m
            `);
        
        zoneLayers.push({ marker, circle });
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
                
                zones.forEach(zone => {
                    addZoneToMap(zone);
                });
            })
            .catch(error => {
                console.error('Error loading zones:', error);
                showMessage('Failed to load existing safe zones', 'error');
            });
    }

    map.on('click', function(e) {
        const coords = e.latlng;
        const radius = parseInt(form.elements.radius.value) || 500;
        
        if (tempMarker) map.removeLayer(tempMarker);
        if (tempCircle) map.removeLayer(tempCircle);

        tempMarker = L.marker(coords).addTo(map)
            .bindPopup('New Zone Location').openPopup();
        
        tempCircle = L.circle(coords, {
            radius: radius,
            color: '#30a2ff',
            fillColor: '#30a2ff',
            fillOpacity: 0.3
        }).addTo(map);
        
        form.elements.latitude.value = coords.lat.toFixed(8);
        form.elements.longitude.value = coords.lng.toFixed(8);
    });

    form.elements.radius.addEventListener('input', function() {
        const radius = parseInt(this.value);
        if (tempCircle && radius >= 100 && radius <= 30000) {
            tempCircle.setRadius(radius);
        }
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!form.elements.latitude.value || !form.elements.longitude.value) {
            showMessage('Please select a location on the map', 'error');
            return;
        }
        
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
        
        messagesDiv.innerHTML = '';
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                
                addZoneToMap(data.zone);
                
                form.reset();
                form.elements.radius.value = 500;
                
                if (tempMarker) map.removeLayer(tempMarker);
                if (tempCircle) map.removeLayer(tempCircle);
                tempMarker = null;
                tempCircle = null;
                
            } else {
                showMessage(data.message || 'Failed to save safe zone', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            let errorMessage = 'An error occurred while saving';
            if (error.errors) {
                errorMessage = Object.values(error.errors).join('<br>');
            } else if (error.message) {
                errorMessage = error.message;
            }
            showMessage(errorMessage, 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        });
    });

    function showMessage(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        messagesDiv.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
    loadAllZones();
});