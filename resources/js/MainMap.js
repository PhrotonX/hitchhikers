export default class MainMap{
    /**
     * Creates MainMap object, initializes the map, and loads the tile layer.
     * @param {string} mapId The ID of the HTML element where map should be displayed.
     * @param {string} nominatimUrl The URL of Nominatim server.
     */
    constructor(mapId, nominatimUrl, webUrl){
        this.map = L.map(mapId, {doubleClickZoom: false, center: [15.038880837376297, 120.6808276221496], zoom: 13,}).locate({setView: true, maxZoom: 20});
        this.markers = new Object();
        // this.markerIcon = null; //Deprecated
        this.markerIcons = new Object();
        this.mapClickCallback = null;
        this.nominatimUrl = nominatimUrl;
        this.webUrl = webUrl;

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(this.map);

        this.setMarkerIcon("default", this.webUrl + "")

        // Handle map markers
        this.map.on('click', (e) => {
            console.log('Coordinates: ' + e.latlng.lat + ", " + e.latlng.lng);

            fetch(this.nominatimUrl + "/reverse?lat=" + e.latlng.lat + "&lon=" + e.latlng.lng + '&format=json&zoom=18&addressdetails=1')
                .then(response => {
                    if(!response.ok){
                        throw new Error("Error: " + response);
                    }
                    console.log(response);

                    return response.json();
                })
                .then(data => {
                    //Add markers
                    var marker = L.marker([e.latlng.lat, e.latlng.lng], {icon: this.markerIcons["default"]}).addTo(this.map);

                    this.mapClickCallback(marker, e, data);
                })
                .catch(error => {
                    console.log("Error: " + error);
                });
        });
    }

    addMarker(tag, latitude, longitude, iconTag){
        this.markers[tag] = L.marker([latitude, longitude], {icon: this.markerIcons[iconTag]}).addTo(this.map);
    }

    /**
     * 
     * @param {*} tag The key or name of the marker.
     * @param {*} object Expects L.marker() object.
     */
    addMarkerObject(tag, object){
        this.markers[tag] = object.addTo(this.map);
    }

    detectLocation(){
        navigator.geolocation.getCurrentPosition((pos) => {
            this.addMarkerObject("currentPos", L.marker([pos.coords.latitude, pos.coords.longitude], {icon: this.markerIcons.currentPos}));
        });
    }

    getMap(){
        return this.map;
    }

    onMapClick(callback){
        this.mapClickCallback = callback;
    }

    /**
     * Define the default marker icon.
     * Must be invoked after calling the constructor.
     * @param {*} markerIconParam The main marker icon.
     * @param {*} markerShadowIcon The shadow icon for main marker icon.
     */
    setMarkerIcon(tag, markerIconParam, markerShadowIcon){
        this.markerIcons[tag] = L.icon({
            iconUrl: markerIconParam,
            shadowUrl: markerShadowIcon,
            
            iconSize:     [38, 95],
            shadowSize:   [50, 64],
            iconAnchor:   [22, 94],
            shadowAnchor: [4, 62], 
            popupAnchor:  [-3, -76]
        });
    }
}