import MainMap from 'MainMap.js';

export default class RideMap extends MainMap{
    constructor(mapId, nominatimUrl, webUrl){
        this.webUrl = webUrl;
        super(mapId, nominatimUrl);
    }

    getRideDestinations(){
        // fetch(this.webUrl + '/api/rides/latitude')
    }

    /**
     * Sets the URL or the route where the marker data is retrieved.
     * @param {*} url The JSON data based on RideDestination[] model.
     */
    // setRideDestinationUrl(url){
        
    // }
}