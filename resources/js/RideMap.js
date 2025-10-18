import MainMap from '../js/MainMap.js';

export default class RideMap extends MainMap{
    constructor(mapId, nominatimUrl, webUrl, rideUrl = '/api/ride/all/destinations?'){
        super(mapId, nominatimUrl);
        this.webUrl = webUrl;
        this.rideUrl = rideUrl;
        
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
}