document.addEventListener("DOMContentLoaded", function () {
    const map = L.map('volunteer-map').setView([12.8797, 121.7740], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const volunteers = JSON.parse(document.getElementById('volunteer-data').textContent);

    volunteers.forEach(volunteer => {
        if (volunteer.latitude && volunteer.longitude) {
            L.marker([volunteer.latitude, volunteer.longitude])
                .addTo(map)
                .bindPopup(`<strong>${volunteer.name}</strong><br>${volunteer.email}`);
        }
    });
});
