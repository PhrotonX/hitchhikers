import MainMap from '../js/MainMap.js';

export default class RideMap extends MainMap{
    constructor(mapId, nominatimUrl, webUrl, rideUrl = '/api/ride/all/destinations?'){
        super(mapId, nominatimUrl, webUrl);
        this.rideUrl = rideUrl;

        this.trackingId = null;
        this.vehicleId = null;
        this.vehicleMarker = null;
        
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
    }

    loadRideDestinations(){
        const bounds = this.map.getBounds();
        const northWest = bounds.getNorthEast();
        const southEast = bounds.getSouthEast();

        var url = this.webUrl + this.rideUrl +
            'lat-north=' + northWest.lat + '&lng-west=' + northWest.lng +
            '&lat-south=' + southEast.lat + '&lng-east=' + southEast.lng;

        console.log("Url: " + url);

        fetch(url
        ).then((response) => {
            return response.json();
        }).then((data) => {
            // console.log("RideDestinations: " + data.results[0].latitude);
            //var marker = L.marker([data.results, e.latlng.lng], {icon: this.markerIcon}).addTo(this.map);

            // data.results.forEach(result => {
            //     L.marker([result.latitude, result.longitude], {icon: this.markerIcon}).addTo(this.map);
            // });

            L.marker([data.results[0].latitude, data.results[0].longitude], {icon: this.markerIcon}).addTo(this.map);
        }).catch((error) => {
            throw new Error(error);
        });
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