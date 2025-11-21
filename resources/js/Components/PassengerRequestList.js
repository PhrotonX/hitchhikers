import Component from '../../js/Components/Component.js';

export default class PassengerRequest extends Component{
    constructor(id, appUrl){
        super(id, appUrl);

        this.list = null;
    }

    onBuildChild(){
        this.element = document.getElementById(this.id);

        var title = document.createElement('p');
        title.innerHTML = "Passenger Requests";

        var title = document.createElement('p');
        title.setAttribute('id', this.id + '-title');
        this.element.appendChild(title);

        var rideSelector = document.createElement('select');
        rideSelector.setAttribute('id', this.id + '-ride-selector');
        this.element.appendChild(rideSelector);


        // fetch(this.appUrl + )
        // this.list = document.createElement('div');
        // this.list.

        
        var item = document.createElement('div');
        item.setAttribute('id', this.id + '-item');
        this.element.appendChild(item);

        var itemTo = document.createElement('p');
        itemTo.setAttribute('id', this.id + '-item-to');
        item.appendChild(itemTo);

        var itemFrom = document.createElement('p');
        itemFrom.setAttribute('id', this.id + '-item-from');
        item.appendChild(itemFrom);

        var itemTime = document.createElement('p');
        itemTime.setAttribute('id', this.id + '-item-time');
        item.appendChild(itemTime);

        var itemMessageLabel = document.createElement('label');
        itemMessageLabel.setAttribute('for', 'message');
        item.appendChild(itemMessageLabel);

        var itemMessageInput = document.createElement('input');
        itemMessageInput.setAttribute('type', 'text');
        itemMessageInput.setAttribute('name', 'message');
        itemMessageInput.setAttribute('id', this.id + '-item-message');
        item.appendChild(itemMessageInput);

        var btnAccept = document.createElement('button');
        btnAccept.setAttribute('type', 'button');
        btnAccept.setAttribute('id', this.id + '-item-btn-accept');
        item.appendChild(btnAccept);

        var btnReject = document.createElement('button');
        btnReject.setAttribute('type', 'button');
        btnReject.setAttribute('id', this.id + '-item-btn-reject');
        item.appendChild(btnReject);

    }
}