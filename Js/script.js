var map = L.map('map').setView([36.44998427874896, 28.2230069811949], 15);

L.tileLayer('https://api.maptiler.com/maps/streets-v2/{z}/{x}/{y}.png?key=PPsH78sewkBj5m7xeL2G', {
    attribution: '&copy; MapTiler & OpenStreetMap'
}).addTo(map);

var photoImg = '<img src="Images/campus-overview.jpg" height="120" width="200"/>';

L.marker([36.44998015440768, 28.222938979802368]).addTo(map)
    .bindPopup('<strong>Μητροπoλιτικό Κολλέγιο Ρόδου</strong><br><br>' + photoImg);