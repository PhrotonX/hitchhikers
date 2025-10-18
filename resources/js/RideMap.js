import MainMap from '../js/MainMap.js';

export default class RideMap extends MainMap{
    constructor(mapId, nominatimUrl, webUrl, rideUrl = '/api/ride/all/destinations?'){
        super(mapId, nominatimUrl);
        this.webUrl = webUrl;
        this.rideUrl = rideUrl;
        
        //@TODO: Use proper event listener values and parameters.
        // this.map.on('', () => {
            //@TODO: Remove markers.
            var data = this.getRideDestinations();
        // });
        
    }

    getRideDestinations(){
        const bounds = this.map.getBounds();
        const northWest = bounds.getNorthEast();
        const southEast = bounds.getSouthEast();

        var url = this.webUrl + this.rideUrl +
            'lat-north=' + northWest.lat + '&lng-west=' + northWest.lng +
            '&lat-south=' + southEast.lat + '&lng-east=' + southEast.lng;

        console.log("Url: " + url);

        fetch(url
        ).then((response) => {
            // return response.json();
            return response;
        }).then((data) => {
            console.log(data);

            return data;
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