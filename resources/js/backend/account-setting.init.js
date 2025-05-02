mapboxgl.accessToken = window.mapboxToken;
const map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [109.3333, -0.0333], // Pontianak coordinates
    zoom: 12,
    maxBounds: [
        [109.2, -0.2], // Southwest coordinates
        [109.5, 0.1]   // Northeast coordinates
    ]
});
