import MainMap from 'MainMap.js';

export default class RideMap extends MainMap{
    constructor(mapId, nominatimUrl, webUrl){
        this.webUrl = webUrl;
        super(mapId, nominatimUrl);
    }

    getRideDestinations(){
        const bounds = this.map.getBounds();
        const northWest = bounds.getNorthEast();
        const southEast = bounds.getSouthEast();

        fetch(this.webUrl + '/api/ride/all/destinations?' +
            'lat-north=' + northWest.latlng.lat + '&lng-west=' + northWest.latlng.lng +
            '&lat-south=' + southEast.latlng.lat + '&lng-east=' + southEast.latlng.lng
        ).then((response) => {
            return response.json();
        }).then((data) => {
            
        }).catch((error) => {
            throw new Error("Failed retrieving ride destinations: " + error);
        });
    }

    /**
     * Sets the URL or the route where the marker data is retrieved.
     * @param {*} url The JSON data based on RideDestination[] model.
     */
    // setRideDestinationUrl(url){
        
    //}
}