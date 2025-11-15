import Component from '../../js/Components/Component.js';

export default class RideRequest extends Component{
    constructor(id, appUrl){
        super(id, appUrl);
    }

    onBuildChild(){
        this.element = document.getElementById(this.id);

        var rideInformation = document.createElement('div');
        rideInformation.setAttribute('class', 'ride-selector-list-item');
        rideInformation.setAttribute('data-vehicle-id', vehicle_id);

            // console.log(data.rides[vehicle_id]);
            var rideInformationTitle = document.createElement('p');
            rideInformationTitle.setAttribute('class', 'title');
            rideInformationTitle.innerHTML = data.rides[vehicle_id][j].ride_name;
            rideInformation.appendChild(rideInformationTitle);

            var rideInformationDescription = document.createElement('p');
            rideInformationDescription.setAttribute('class', 'description');
            rideInformationDescription.innerHTML = "Description: " + data.rides[vehicle_id][j].description;
            rideInformation.appendChild(rideInformationDescription);

            var rideInformationOn = document.createElement('p');
            rideInformationOn.innerHTML = "Currently on: Obtaining location..." ;
            this.reverseGeocode(data.results[i].latitude, data.results[i].longitude).then((result) => {
                rideInformationOn.innerHTML = "Currently on: " + result.display_name;
            });
            rideInformation.appendChild(rideInformationOn);

            // var rideInformationFrom = document.createElement('p');
            // rideInformationFrom.innerHTML = "From: Obtaining location..." ;
            // this.reverseGeocode(data.results[i].latitude, data.results[i].longitude).then((result) => {
            //     rideInformationFrom.innerHTML = "Currently on: " + result.display_name;
            // });
            // rideInformation.appendChild(rideInformationFrom);

            // @TODO: Display all selectable ride destinations here...

        var inputDiv = document.createElement('div');
        inputDiv.setAttribute('id', 'ride-request-form');

            var inputPickupTime = document.createElement('input');
            inputPickupTime.setAttribute('name', 'pickup_at')
            // inputPickupTime.setAttribute('type', )

            var inputTime = document.createElement('input');
            inputTime.setAttribute('name', 'time')
            inputTime.setAttribute('type', 'text');

            var inputMessage = document.createElement('textarea');
            inputMessage.setAttribute('name', 'message')

            var inputSubmit = document.createElement('button');
            inputSubmit.setAttribute('type', 'submit');

            var inputReset = document.createElement('button');
            inputReset.setAttribute('type', 'reset');
        
        inputDiv.appendChild(inputPickupTime);
        inputDiv.appendChild(inputTime);
        inputDiv.appendChild(inputMessage);
        inputDiv.appendChild(inputSubmit);
        inputDiv.appendChild(inputReset);

        rideInformation.append(inputDiv);
        this.element.appendChild(rideInformation);
    }
}