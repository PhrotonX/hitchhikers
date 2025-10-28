export default class MainMap{
    /**
     * Creates MainMap object, initializes the map, and loads the tile layer.
     * @param {string} mapId The ID of the HTML element where map should be displayed.
     * @param {string} nominatimUrl The URL of Nominatim server.
     */
    constructor(mapId, nominatimUrl, webUrl){
        this.map = L.map(mapId, {doubleClickZoom: false, center: [15.038880837376297, 120.6808276221496], zoom: 13,}).locate({setView: true, maxZoom: 20});
        this.markers = new Object();
        this.markerIds = new Object();
        // this.markerIcon = null; //Deprecated
        this.markerIcons = new Object();
        this.mapClickCallback = null;
        this.mapPanCallback = null;
        this.nominatimUrl = nominatimUrl;
        this.rideUrl = '/api/ride/all/destinations?';
        this.webUrl = webUrl;

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(this.map);

        // this.setMarkerIcon("default", this.webUrl + "");
    }

    addMarker(tag, latitude, longitude, iconTag){
        this.markers[tag] = L.marker([latitude, longitude], {icon: this.markerIcons[iconTag]}).addTo(this.map);
    }

    /**
     * Adds a marker object created with L.marker();
     * @param {*} tag The key or name of the marker.
     * @param {*} object Expects L.marker() object.
     */
    addMarkerObject(tag, object){
        this.markers[tag] = object.addTo(this.map);
    }

    /**
     * Detects the current device location and adds a marker object on it.
     */
    detectLocation(){
        navigator.geolocation.getCurrentPosition((pos) => {
            this.addMarkerObject("currentPos", L.marker([pos.coords.latitude, pos.coords.longitude], {icon: this.markerIcons.currentPos}));
        });
    }

    getMap(){
        return this.map;
    }

    /**
     * Sets the functionality for map click event.
     * 
     * This returns an event parameter containing the latitude and longitude of a clicked area from the map.
     * @param {*} callback 
     */
    onMapClick(callback){
        this.mapClickCallback = callback;
    }

    onMapPan(callback){
        this.mapPanCallback = callback;
    }

    /**
     * Retrieves latitude and longitude data.
     */
    reverseEngineer(e, lat, lng, pinToMap = true){
        fetch(this.nominatimUrl + "/reverse?lat=" + lat + "&lon=" + lng + '&format=json&zoom=18&addressdetails=1')
            .then(response => {
                if(!response.ok){
                    throw new Error("Error: " + response);
                }
                console.log(response);

                return response.json();
            })
            .then(data => {
                if(pinToMap){
                    //Add markers
                    var marker = L.marker([lat, lng], {icon: this.markerIcons["default"]}).addTo(this.map);

                    this.mapClickCallback(marker, e, data);
                }
            })
            .catch(error => {
                console.log("Error: " + error);
            });
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

    /**
     * Replaces existing map click event into an event that retrieves a reverse-engineered data based on map click location.
     */
    enableClickToAddMultipleMarkers(){
        this.map.on('click', (e) => {
            // console.log('Coordinates: ' + e.latlng.lat + ", " + e.latlng.lng);
            this.reverseEngineer(e, e.latlng.lat, e.latlng.lng);
        });
    }

    
}