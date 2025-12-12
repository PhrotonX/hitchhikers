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

        if(navigator.geolocation){
            this.trackingId = navigator.geolocation.watchPosition((position) => {
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;

                var rideRequestFromLabel = document.getElementById('ride-request-from-label');

                var rideRequestFromLatitude = document.getElementById('ride-request-from-latitude');
                rideRequestFromLatitude.value = latitude;

                var rideRequestFromLongitude = document.getElementById('ride-request-from-longitude');
                rideRequestFromLongitude.value = longitude;

                this.map.reverseGeocode(latitude, longitude, (data) => {
                    rideRequestFromLabel.innerHTML = data.display_name + " - " + latitude + ", " + longitude;
                });
            }, (error) => {
            console.log("Error: " + error);
        });
        }else{
            alert("Geolocation is turned off or not supported by this device");
        }
    }

    onReverseGeocode(data){
        let pickAtField = document.getElementById('ride-request-pickup-at');
        pickAtField.value = data.display_name;
    }

    onSelectMapArea(e, data){
        let rideRequestToLabel = document.getElementById('ride-request-to-label');
        rideRequestToLabel.innerHTML = data.display_name + " - " + e.latlng.lat + ", " + e.latlng.lng;

        let rideRequestToLatitude = document.getElementById('ride-request-to-latitude');
        rideRequestToLatitude.value = e.latlng.lat;

        let rideRequestToLongitude = document.getElementById('ride-request-to-longitude');
        rideRequestToLongitude.value = e.latlng.lng;

        // Calculate and display distance in kilometers
        let fromLat = parseFloat(document.getElementById('ride-request-from-latitude').value);
        let fromLng = parseFloat(document.getElementById('ride-request-from-longitude').value);
        let toLat = parseFloat(e.latlng.lat);
        let toLng = parseFloat(e.latlng.lng);
        if (!isNaN(fromLat) && !isNaN(fromLng) && !isNaN(toLat) && !isNaN(toLng)) {
            let distanceKm = this.calculateDistance(fromLat, fromLng, toLat, toLng);
            rideRequestToLabel.innerHTML += ` (<span id="ride-request-distance">${distanceKm.toFixed(2)} km</span>)`;
            this.updatePrice(distanceKm);
        }
    }

    // Haversine formula for distance in kilometers
    calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Radius of the earth in km
        const dLat = this.deg2rad(lat2-lat1);
        const dLon = this.deg2rad(lon2-lon1);
        const a =
            Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(this.deg2rad(lat1)) * Math.cos(this.deg2rad(lat2)) *
            Math.sin(dLon/2) * Math.sin(dLon/2)
            ;
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        const d = R * c; // Distance in km
        return d;
    }

    deg2rad(deg) {
        return deg * (Math.PI/180);
    }

    updatePrice(distanceKm) {
        // Get fare_rate and minimum_rate from window variables (set in Blade template)
        let fareRate = typeof window.fare_rate !== 'undefined' ? parseFloat(window.fare_rate) : null;
        let minimumRate = typeof window.minimum_rate !== 'undefined' ? parseFloat(window.minimum_rate) : null;
        let priceInput = document.getElementById('ride-request-price');
        let priceValue = document.getElementById('price-value');

        if (fareRate === null || minimumRate === null) {
            priceInput.value = '';
            priceValue.innerText = 'N/A';
            return;
        }

        // Pricing logic: first 5km = minimum_rate, after that fare_rate per km
        let price = 0;
        if (distanceKm <= 5) {
            price = minimumRate;
        } else {
            price = minimumRate + (distanceKm - 5) * fareRate;
        }
        price = Math.max(price, minimumRate);
        priceInput.value = price.toFixed(2);
        priceValue.innerText = price.toFixed(2);
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