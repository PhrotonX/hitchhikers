import Page from '../js/Page.js';

export default class CreateRideRequestPage extends Page{
    constructor(appUrl, map, rideId){
        super(appUrl);

        this.map = map;
        this.rideId = rideId;

        this.getRides(this.rideId);
        this.map.enableClickToAddSingleMarker();

        this.map.onMapClick((marker, e, data) => {
            this.onReverseGeocode(data);

            this.onSelectMapArea(e, data);

            this.setRequestIds({
                ride_id: null,
                id: null
            });
        });

        this.map.setOnRideMarkerClick((e, data) => {
            console.log(data);

            this.map.removeTemporaryMarker();

            this.setRequestIds(data);

            this.map.reverseGeocode(data.latitude, data.longitude, (data) => {
                this.onReverseGeocode(data);
                this.onSelectMapArea(e, data);
            });

            
            
        });
    }

    onReverseGeocode(data){
        let pickAtField = document.getElementById('ride-request-pickup-at');
        pickAtField.value = data.display_name;
    }

    onSelectMapArea(e, data){
        // console.log(e);
        // console.log(data);

        let rideRequestToLabel = document.getElementById('ride-request-to-label');
        rideRequestToLabel.innerHTML = data.display_name + " - " + e.latlng.lat + ", " + e.latlng.lng;
    }

    onInitializePage(){
        
    }

    getRides(rideId){
        this.map.retrieveRideMarkers(rideId, true)();
    }

    setRequestIds(data){
        // let rideIdField = document.getElementById('ride-request-ride-id');
        // rideIdField.value = data.ride_id;

        let destinationIdField = document.getElementById('ride-request-destination-id');
        destinationIdField.value = data.id;
    }
}