import MainMap from '../js/MainMap.js';

export default class RideMap extends MainMap{
    constructor(mapId, nominatimUrl, webUrl){
        super(mapId, nominatimUrl, webUrl);
        this.rideUrl = '/api/ride/all/destinations?';
        this.rideMarkers = new Object();
        this.vehicleMarkers = new Object();
        this.trackingId = null;
        this.vehicleId = null;
        this.vehicleMarker = null;
        this.vehicleUrl = '/vehicle?';
        this.onVehicleMarkerClick = null;
        this.onRideMarkerClick = null;
        
        this.rideMarkers = L.markerClusterGroup();
        this.vehicleMarkers = L.markerClusterGroup();
        //@TODO: Use proper event listener values and parameters.
        // this.map.on('', () => {
            //@TODO: Remove markers.
            //var data = this.getRideDestinations();
        // });
        // console.log("data: " + data);

        // var data = this.loadRideDestinations();

        // data.results.forEach(element => {
        //     console.log(element);
        // });

        // this.loadRideDestinations();

        this.map.addLayer(this.rideMarkers);
        this.map.addLayer(this.vehicleMarkers);
    }

    enablePanToRetrieveRideMarkers(){
        this.map.on('moveend', this.retrieveRideMarkers());
    }

    /**
     * Replaces existing map pan event into an event that retrieves all map markers within the bounding box of the map view.
     */
    enablePanToRetrieveVehicleMarkers(){
        this.map.on('moveend', this.retrieveVehicleMarkers());
    }

    setOnRideMarkerClick(callback){
        this.onRideMarkerClick = callback;
    }

    setOnVehicleMarkerClick(callback){
        this.onVehicleMarkerClick = callback;
    }

    /**
     * Retrieves map markers within a map boundary (outdated code)
     */
    retrieveRideMarkers(){
        return () => {
            // console.log("Map panned!");

            //Obtain map bounds to specify the area of map where markers must be obtained.
            const bounds = this.map.getBounds();
            const northWest = bounds.getNorthEast();
            const southEast = bounds.getSouthEast();

            var url = this.webUrl + this.rideUrl +
                'lat-north=' + northWest.lat + '&lng-west=' + northWest.lng +
                '&lat-south=' + southEast.lat + '&lng-east=' + southEast.lng;

            // console.log("Url: " + url);

            fetch(url
            ).then((response) => {
                return response.json();
            }).then((data) => {

                //Populate the map with markers
                var count = Object.keys(data.results).length;
                for(let i = 0; i < count; i++){

                    //Check if the marker already exists to avoid marker duplication.
                    if(!this.rideMarkers.hasLayer(this.markers["ride-" + data.results[i].id])){
                        var marker = L.marker([data.results[i].latitude, data.results[i].longitude], {icon: this.markerIcons["default"]});

                        //Setup marker click listener.
                        marker.on('click', (e) => {
                            if(this.onRideMarkerClick){
                                this.onRideMarkerClick(e, data.results[i]);
                            }
                        });

                        //Obtain marker ID for duplication detection.
                        this.markers["ride-" + data.results[i].id] = marker;

                        // Add the marker into the map.
                        this.rideMarkers.addLayer(marker);
                    }
                }

                // console.log("Count: " + Object.keys(this.markers).length);

            }).catch((error) => {
                throw new Error(error);
            });
        }
    }

    retrieveVehicleMarkers(){
        return () => {
            // console.log("Map panned!");

            //Obtain map bounds to specify the area of map where markers must be obtained.
            const bounds = this.map.getBounds();
            const northWest = bounds.getNorthEast();
            const southEast = bounds.getSouthEast();

            var url = this.webUrl + this.vehicleUrl +
                'lat-north=' + northWest.lat + '&lng-west=' + northWest.lng +
                '&lat-south=' + southEast.lat + '&lng-east=' + southEast.lng;

            // console.log("Url: " + url);

            fetch(url
            ).then((response) => {
                return response.json();
            }).then((data) => {

                //Populate the map with markers
                var count = Object.keys(data.results).length;
                for(let i = 0; i < count; i++){

                    //Check if the marker already exists to avoid marker duplication.
                    if(!this.vehicleMarkers.hasLayer(this.markers["vehicle-" + data.results[i].id])){
                        var marker = L.marker([data.results[i].latitude, data.results[i].longitude], {icon: this.markerIcons["default"]});

                        //Setup marker click listener.
                        marker.on('click', (e) => {
                            if(this.onVehicleMarkerClick){
                                this.onVehicleMarkerClick(e, data.results[i]);
                            }
                        });

                        //Obtain marker ID for duplication detection.
                        this.markers["vehicle-" + data.results[i].id] = marker;

                        // Add the marker into the map.
                        this.vehicleMarkers.addLayer(marker);
                    }
                }

                // console.log("Count: " + Object.keys(this.markers).length);

            }).catch((error) => {
                throw new Error(error);
            });
        }
    }

    /**
     * Sets the URL or the route where the marker data is retrieved.
     * @param {*} url The JSON data based on RideDestination[] model.
     */
    setRideDestinationUrl(url){
        this.rideUrl = url;
    }

    startLiveTracking(onMarkerClick, vehicle_id){
        this.vehicleId = vehicle_id;
        //Get current location
        if(navigator.geolocation){
            this.trackingId = navigator.geolocation.watchPosition((position) => {
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;

                console.log("Live Marker: Latitude: " + latitude);
                console.log("Live Marker: Longitude: " + longitude);

                //@TODO: Change the map marker color from gray to blue.

                //Position the map where the current location is pointing to.
                this.map.setView([latitude, longitude], 16);

                //Update the position of the marker indicating the vehicle's position.
                this.markers.currentPos.setLatLng([latitude, longitude]);

                //Save the position data into the database.
                //=========================================
                fetch(this.webUrl + '/vehicle/'+this.vehicleId+'/update-location', {
                    method: "PATCH",
                    body: JSON.stringify({
                        latitude: latitude,
                        longitude: longitude,
                    }),
                    headers: {
                        "Content-type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-Token": document.querySelector('meta[name=csrf-token]').content,
                    },
                }).then((response) => {
                    return response.json();
                }).then((data) => {
                    console.log(data);
                }).catch((error) => {
                    throw new Error(error);
                });
                //=========================================

            }, (error) => {
            console.log("Error: " + error);
        });
        }else{
            alert("Geolocation is turned off or not supported by this device");
        }
    }

    stopLiveTracking(tag){
        navigator.geolocation.clearWatch(this.trackingId);

        //@TODO: Change the marker color from blue to gray.
    }
}