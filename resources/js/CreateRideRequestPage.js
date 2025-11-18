import Page from '../js/Page.js';

export default class CreateRideRequestPage extends Page{
    constructor(appUrl, map, rideId){
        super(appUrl);

        this.map = map;
        this.rideId = rideId;

        this.getRides(this.rideId);
    }

    onInitializePage(){
        
    }

    getRides(rideId){
        this.map.retrieveRideMarkers(rideId, true)();
    }
}