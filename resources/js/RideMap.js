import MainMap from '../js/MainMap.js';

/**
 * Displays a map with ride and vehicle handling.
 * Expects the following marker icon to be loaded: current, defaultPos, active_vehicle, inactive_vehicle.
 */
export default class RideMap extends MainMap{
    constructor(mapId, nominatimUrl, webUrl, properties){
        super(mapId, nominatimUrl, webUrl);
        this.isAuth = properties.is_auth ?? false;
        this.isDriver = properties.is_driver ?? false;
        this.rideDestinationUrl = '/ride/destinations';
        this.rideMarkers = new Object();
        this.vehicleMarkers = new Object();
        this.trackingId = null;
        this.rideSelectorList = null;
        this.vehicleId = null;
        this.vehicleMarker = null;
        this.vehicleUrl = '/vehicle';
        this.onVehicleMarkerClick = null;
        this.onRideMarkerClick = null;
        
        this.cachedMarkers = L.markerClusterGroup();
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

        this.map.addLayer(this.cachedMarkers);
        this.map.addLayer(this.rideMarkers);
        this.map.addLayer(this.vehicleMarkers);
    }

    clearRideSelectorList(){
        this.rideSelectorList.innerHTML = "";
    }

    enablePanToRetrieveAllRideMarkers(){
        // Prevent adding duplicate listeners
        if(!this._rideRetrievalEnabled){
            this._rideRetrievalEnabled = true;
            this.map.on('moveend', this.retrieveAllRideMarkers());
        }
    }

    /**
     * Replaces existing map pan event into an event that retrieves all map markers within the bounding box of the map view.
     */
    enablePanToRetrieveVehicles(){
        // Prevent adding duplicate listeners
        if(!this._vehicleRetrievalEnabled){
            this._vehicleRetrievalEnabled = true;
            this.map.on('moveend', this.retrieveVehicles());
        }
    }

    setOnRideMarkerClick(callback){
        this.onRideMarkerClick = callback;
    }

    setOnVehicleMarkerClick(callback){
        this.onVehicleMarkerClick = callback;
    }

    /**
     * Retrieves all map markers within a map boundary.
     * 
     * Returns a callback function.
     */
    retrieveAllRideMarkers(){
        return () => {
            // console.log("Map panned!");

            //Obtain map bounds to specify the area of map where markers must be obtained.
            const bounds = this.map.getBounds();
            const northWest = bounds.getNorthEast();
            const southEast = bounds.getSouthEast();

            var url = this.webUrl + this.rideDestinationUrl + "?" +
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

    /**
     * Retrieves rides based on a vehicle ID.
     * 
     * Returns a callback function.
     */
    retrieveRides(vehicleId){
        return () => {
            var url = this.webUrl + this.vehicleUrl + "/" + vehicleId + "/rides";

            // console.log("Url: " + url);

            return fetch(url
                ).then((response) => {
                    return response.json();
                }).then((data) => {
                    return data;
                }).catch((error) => {
                    throw new Error(error);
                });
        }
    }

    /**
     * Retrieves map markers of a ride destination for a specific ride and stores the markers. This function
     * also removes the ride-specific map markers before populating the map with markers.
     * Returns a callback function.
     * @param {*} rideId 
     * @returns 
     */
    retrieveRideMarkers(rideId, hasLine = false){
        return () => {
            // console.log("Map panned!");

            //Obtain map bounds to specify the area of map where markers must be obtained.
            const bounds = this.map.getBounds();
            const northWest = bounds.getNorthEast();
            const southEast = bounds.getSouthEast();

            var url = this.webUrl + this.rideDestinationUrl + "/" + rideId;

            var latlngs = [];

            // console.log("Url: " + url);

            fetch(url
            ).then((response) => {
                return response.json();
            }).then((data) => {
                //Clear the cached ride map markers.
                this.cachedMarkers.clearLayers();

                //Populate the map with markers
                var count = Object.keys(data.results).length;
                for(let i = 0; i < count; i++){

                    //Check if the marker already exists to avoid marker duplication.
                    if(!this.cachedMarkers.hasLayer(this.markers["ride-" + data.results[i].id])){
                        var marker = L.marker([data.results[i].latitude, data.results[i].longitude], {icon: this.markerIcons["default"]});

                        latlngs.push([data.results[i].latitude, data.results[i].longitude]);


                        //Setup marker click listener.
                        marker.on('click', (e) => {
                            if(this.onRideMarkerClick){
                                this.onRideMarkerClick(e, data.results[i]);
                            }
                        });

                        //Obtain marker ID for duplication detection.
                        this.markers["ride-" + data.results[i].id] = marker;

                        // Add the marker into the map.
                        this.cachedMarkers.addLayer(marker);
                    }
                }

                // Draw the line on the map.
                if(hasLine){
                    var polyline = L.polyline(latlngs, {
                        color: 'blue'
                    }).addTo(this.map);
                }

                // console.log("Count: " + Object.keys(this.markers).length);

                return data;

            }).catch((error) => {
                throw new Error(error);
            });
        }
    }

    /**
     * Retrieves vehicle markers and displays it on a map.
     * @returns A callback function.
     */
    retrieveVehicles(){
        return () => {
            // console.log("Map panned!");

            //Obtain map bounds to specify the area of map where markers must be obtained.
            const bounds = this.map.getBounds();
            const northWest = bounds.getNorthEast();
            const southEast = bounds.getSouthEast();

            var url = this.webUrl + this.vehicleUrl + "?" +
                'lat-north=' + northWest.lat + '&lng-west=' + northWest.lng +
                '&lat-south=' + southEast.lat + '&lng-east=' + southEast.lng;

            // console.log("Url: " + url);

            var rideSelector = document.getElementById('ride-selector');

            if(this.rideSelectorList == null){
                this.rideSelectorList = document.createElement('div');
                this.rideSelectorList.setAttribute('class', 'ride-selector-list');
                rideSelector.appendChild(this.rideSelectorList);
                
                // Add click listener only once when creating the list
                this.rideSelectorList.addEventListener('click', (e) => {
                    // Find the clicked ride item and get its data
                    const rideItem = e.target.closest('.ride-selector-list-item');
                    if(rideItem && this.onVehicleMarkerClick){
                        const vehicleId = rideItem.getAttribute('data-vehicle-id');
                        const vehicleData = this.rideSelectorList._vehicleData[vehicleId];
                        if(vehicleData){
                            this.onVehicleMarkerClick(e, vehicleData);
                            this.setView(vehicleData.latitude, vehicleData.longitude);
                        }
                    }
                });
            }else{
                // Avoid duplicate items.
                this.clearRideSelectorList();
            }
            
            // Store vehicle data for the click handler
            if(!this.rideSelectorList._vehicleData){
                this.rideSelectorList._vehicleData = {};
            }
            
            
            fetch(url
            ).then((response) => {
                return response.json();
            }).then((data) => {

                //Populate the map with markers
                var count = Object.keys(data.results).length;
                for(let i = 0; i < count; i++){

                    if(!this.isDriver){
                        this.buildRideSelector(data, i);
                    }

                    //Check if the marker already exists to avoid marker duplication.
                    if(!this.vehicleMarkers.hasLayer(this.markers["vehicle-" + data.results[i].id])){
                        //Decide on the marker icon depending on the marker state [INCOMPLETE].
                        var markerIcon;
                        if(data.results[i].status == "active"){
                            markerIcon = this.markerIcons["active_vehicle"];
                        }else{
                            markerIcon = this.markerIcons["inactive_vehicle"];
                        }
                        
                        var marker = L.marker([data.results[i].latitude, data.results[i].longitude], {icon: markerIcon});

                        //Setup marker click listener.
                        marker.on('click', (e) => {

                            //Get vehicle rides.

                            if(this.onVehicleMarkerClick){
                                this.onVehicleMarkerClick(e, data.results[i]);
                                this.setView(data.results[i].latitude, data.results[i].longitude);
                            }
                        });

                        //Obtain marker ID for duplication detection.
                        this.markers["vehicle-" + data.results[i].id] = marker;

                        // Add the marker into the map.
                        this.vehicleMarkers.addLayer(marker);
                    }
                }

                // var count = Object.keys(data.rides).length;
                // for(let i = 0; i < count; i++){
                    
                // }

                // console.log("Count: " + Object.keys(this.markers).length);

            }).catch((error) => {
                throw new Error(error);
            });
        }
    }

    buildRideSelector(data, i){
        let vehicle_id = data.results[i].id;
                    
        // Store vehicle data for click handler
        this.rideSelectorList._vehicleData[vehicle_id] = data.results[i];

        var rideCount = Object.keys(data.rides[vehicle_id]).length;
        for(let j = 0; j < rideCount; j++){
            // if(data.rides[vehicle_id][j] != "active"){
            //     continue;
            // }

            var rideSelectorListItem = document.createElement('div');
            rideSelectorListItem.setAttribute('class', 'ride-selector-list-item');
            rideSelectorListItem.setAttribute('data-vehicle-id', vehicle_id);

                // console.log(data.rides[vehicle_id]);
                var rideSelectorListItemTitle = document.createElement('p');
                rideSelectorListItemTitle.setAttribute('class', 'title');
                rideSelectorListItemTitle.innerHTML = data.rides[vehicle_id][j].ride_name;
                rideSelectorListItem.appendChild(rideSelectorListItemTitle);

                var rideSelectorListItemDescription = document.createElement('p');
                rideSelectorListItemDescription.setAttribute('class', 'description');
                rideSelectorListItemDescription.innerHTML = "Description: " + data.rides[vehicle_id][j].description;
                rideSelectorListItem.appendChild(rideSelectorListItemDescription);

                var rideSelectorListItemOn = document.createElement('p');
                rideSelectorListItemOn.innerHTML = "Currently on: Obtaining location..." ;
                this.reverseGeocode(data.results[i].latitude, data.results[i].longitude).then((result) => {
                    rideSelectorListItemOn.innerHTML = "Currently on: " + result.display_name;
                });
                rideSelectorListItem.appendChild(rideSelectorListItemOn);

                // var rideSelectorListItemFrom = document.createElement('p');
                // rideSelectorListItemFrom.innerHTML = "From: Obtaining location..." ;
                // this.reverseGeocode(data.results[i].latitude, data.results[i].longitude).then((result) => {
                //     rideSelectorListItemFrom.innerHTML = "Currently on: " + result.display_name;
                // });
                // rideSelectorListItem.appendChild(rideSelectorListItemFrom);
            
            this.rideSelectorList.appendChild(rideSelectorListItem);
        }
    }

    /**
     * Sets the URL or the route where the marker data is retrieved.
     * @param {*} url The JSON data based on RideDestination[] model.
     */
    setRideDestinationUrl(url){
        this.rideDestinationUrl = url;
    }

    /**
     * Tracks the 
     * @param {*} vehicle_id 
     */
    startLiveTracking(vehicle_id){
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
    }
}