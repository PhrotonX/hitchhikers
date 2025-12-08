import Component from '../../js/Components/Component.js';

export default class PassengerRequestList extends Component{
    constructor(id, appUrl, nominatimUrl, driverId){
        super(id, appUrl);

        this.nominatimUrl = nominatimUrl;

        this.list = null;
        this.driverId = driverId;

        this.element = document.getElementById(this.id);

        var title = document.createElement('p');
        title.innerHTML = "Passenger Requests";

        var title = document.createElement('p');
        title.setAttribute('id', this.id + '-title');
        this.element.appendChild(title);

        var rideSelector = document.createElement('select');
        rideSelector.setAttribute('id', this.id + '-ride-selector');
        this.element.appendChild(rideSelector);
        
        fetch(this.appUrl + '/driver/'+this.driverId+'/ride/')
        .then((response) => {
            return response.json();
        })
        .then((data) => {

            console.log(data);

            let count = Object.keys(data).length;

            for(let i = 0; i < count; i++){    
                var rideSelectorItem = document.createElement('option');
                rideSelectorItem.value = data[0][i].id;
                rideSelectorItem.innerHTML = data[0][i].ride_name;

                rideSelector.appendChild(rideSelectorItem);
            }
        })
        .catch((error) => {
            throw new Error(error);
        });

        this.list = document.createElement('div');
        this.list.setAttribute('id', this.id + '-items');

        this.element.appendChild(this.list);
    }

    onBuildChild(){
        
    }

    displayItems(rideId){
        const url = this.appUrl + '/ride/' + rideId + '/requests';
        console.log(url);

        fetch(url)
        .then((response) => {
            return response.json();
        }).then((data) => {
            console.log(data);
            let count = Object.keys(data).length;

            for(let i = 0; i < count; i++){
                console.log(data[0][i]);
                var item = document.createElement('div');
                item.setAttribute('id', this.id + '-' + data[0][i].id + '-item');

                    var itemTo = document.createElement('p');
                    itemTo.setAttribute('id', this.id + '-' + data[0][i].id + '-item-to');
                    itemTo.innerHTML = "To: Retrieving address...";
                    this.reverseGeocode(data[0][i].to_latitude, data[0][i].to_longitude).then((result) => {
                        itemTo.innerHTML = "To: " + result.display_name;
                    });
                    item.appendChild(itemTo);

                    var itemFrom = document.createElement('p');
                    itemFrom.setAttribute('id', this.id + '-' + data[0][i].id + '-item-from');
                    itemFrom.innerHTML = "From: Retrieving address...";
                    this.reverseGeocode(data[0][i].from_latitude, data[0][i].from_longitude).then((result) => {
                        itemFrom.innerHTML = "To: " + result.display_name;
                    });
                    item.appendChild(itemFrom);

                    var itemTime = document.createElement('p');
                    itemTime.setAttribute('id', this.id + '-' + data[0][i].id + '-item-time');
                    itemTime.innerHTML = "Time: " + data[0][i].time;
                    item.appendChild(itemTime);

                    var itemMessageLabel = document.createElement('label');
                    itemMessageLabel.setAttribute('for', 'message');
                    itemMessageLabel.innerHTML = "Your Message: ";
                    item.appendChild(itemMessageLabel);

                    var itemMessageInput = document.createElement('input');
                    itemMessageInput.setAttribute('type', 'text');
                    itemMessageInput.setAttribute('name', 'message');
                    itemMessageInput.setAttribute('id', this.id + '-' + data[0][i].id + '-item-message');
                    item.appendChild(itemMessageInput);

                    var btnAccept = document.createElement('button');
                    btnAccept.setAttribute('type', 'button');
                    btnAccept.setAttribute('id', this.id + '-' + data[0][i].id + '-item-btn-accept');
                    btnAccept.innerHTML = "Accept";
                    item.appendChild(btnAccept);

                    var btnReject = document.createElement('button');
                    btnReject.setAttribute('type', 'button');
                    btnReject.setAttribute('id', this.id + '-' + data[0][i].id + '-item-btn-reject');
                    btnReject.innerHTML = "Reject";
                    item.appendChild(btnReject);

                this.list.appendChild(item);
            }
        })
        .catch((error) => {
            throw new Error(error);
        });
    }

    destroyItems(){
        while(this.list.lastElementChild){
            this.list.removeChild(this.list.lastElementChild);
        }
    }

    reverseGeocode(lat, lng, callback = null){
        return fetch(this.nominatimUrl + "/reverse?lat=" + lat + "&lon=" + lng + '&format=json&zoom=18&addressdetails=1')
            .then(response => {
                return response.json();
            })
            .then((data) => {
                // console.log(data);
                if(callback){
                    callback(data);
                }
                return data;
            })
            .catch(error => {
                throw new Error(error);
            });
    }
}