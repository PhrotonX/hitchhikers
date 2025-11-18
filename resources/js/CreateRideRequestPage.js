import Page from '../js/Page.js';

export default class CreateRideRequestPage extends Page{
    constructor(appUrl, map, rideId){
        super(appUrl);

        this.map = map;
        this.rideId = rideId;
    }

    onInitializePage(){
        // this.getRides(this.rideId);
    }

    getRides(rideId){
        this.map.retrieveRideMarkers(rideId)();
    }
}