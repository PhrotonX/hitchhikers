import Component from '../../js/Components/Component.js';

export default class PassengerRequestList extends Component{
    constructor(id, appUrl, nominatimUrl, driverId, rideMap = null){
        super(id, appUrl);

        this.nominatimUrl = nominatimUrl;
        this.rideMap = rideMap;

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
            let count = Object.keys(data[0]).length;

            for(let i = 0; i < count; i++){
                console.log(data[0][i]);
                var item = document.createElement('div');
                item.setAttribute('id', this.id + '-' + data[0][i].id + '-item');

                    var itemTo = document.createElement('p');
                    const requestId = data[0][i].id; // Capture the ID for closure
                    const toLatitude = data[0][i].to_latitude;
                    const toLongitude = data[0][i].to_longitude;
                    const fromLatitude = data[0][i].from_latitude;
                    const fromLongitude = data[0][i].from_longitude;
                    
                    itemTo.setAttribute('id', this.id + '-' + requestId + '-item-to');
                    itemTo.innerHTML = "To: Retrieving address...";
                    this.reverseGeocode(toLatitude, toLongitude).then((result) => {
                        itemTo.innerHTML = 'To: <span id="'+this.id + '-' + requestId + '-item-to-span'+'">' + result.display_name + "</span>";
                    });
                    item.appendChild(itemTo);

                    var itemFrom = document.createElement('p');
                    itemFrom.setAttribute('id', this.id + '-' + requestId + '-item-from');
                    itemFrom.innerHTML = "From: Retrieving address...";
                    this.reverseGeocode(fromLatitude, fromLongitude).then((result) => {
                        itemFrom.innerHTML = 'From: <span id="'+this.id + '-' + requestId + '-item-from-span'+'">' + result.display_name + "</span>";
                    });
                    item.appendChild(itemFrom);

                    var itemTime = document.createElement('p');
                    itemTime.setAttribute('id', this.id + '-' + data[0][i].id + '-item-time');
                    itemTime.innerHTML = "Time: " + data[0][i].time;
                    item.appendChild(itemTime);

                    var itemProfit = document.createElement('p');
                    itemProfit.setAttribute('id', this.id + '-' + data[0][i].id + '-item-profit');
                    itemProfit.innerHTML = "Estimated Profit: PHP" + data[0][i].price;
                    item.appendChild(itemProfit);

                    var itemStatus = document.createElement('p');
                    itemStatus.setAttribute('id', this.id + '-' + data[0][i].id + '-item-status');
                    itemStatus.innerHTML = "<strong>Status: </strong>" + data[0][i].status;
                    itemStatus.style.fontWeight = 'bold';
                    item.appendChild(itemStatus);

                    // Modal div for message input and action buttons
                    var modalDiv = document.createElement('div');
                    modalDiv.setAttribute('id', this.id + '-' + data[0][i].id + '-item-modal');
                    modalDiv.style.display = 'none';

                    // var itemMessageLabel = document.createElement('label');
                    // itemMessageLabel.setAttribute('for', 'message');
                    // itemMessageLabel.innerHTML = "Your Message: ";
                    // modalDiv.appendChild(itemMessageLabel);

                    // var itemMessageInput = document.createElement('input');
                    // itemMessageInput.setAttribute('type', 'text');
                    // itemMessageInput.setAttribute('name', 'message');
                    // itemMessageInput.setAttribute('id', this.id + '-' + data[0][i].id + '-item-message');
                    // modalDiv.appendChild(itemMessageInput);

                    var btnAccept = document.createElement('button');
                    btnAccept.setAttribute('type', 'button');
                    btnAccept.setAttribute('id', this.id + '-' + data[0][i].id + '-item-btn-accept');
                    btnAccept.innerHTML = "Accept";
                    modalDiv.appendChild(btnAccept);

                    var btnReject = document.createElement('button');
                    btnReject.setAttribute('type', 'button');
                    btnReject.setAttribute('id', this.id + '-' + data[0][i].id + '-item-btn-reject');
                    btnReject.innerHTML = "Reject";
                    modalDiv.appendChild(btnReject);

                    item.appendChild(modalDiv);

                    // Apply styling based on current status
                    const currentStatus = data[0][i].status;
                    console.log('Creating item with status:', currentStatus, 'ID:', requestId);
                    
                    if (currentStatus === 'approved') {
                        itemStatus.innerHTML = "<strong>Status: </strong><span style='color: green;'>✓ Approved</span>";
                        itemStatus.style.color = 'green';
                        item.style.backgroundColor = '#d4edda';
                        item.style.border = '2px solid #28a745';
                        item.style.cursor = 'pointer'; // Changed from 'default' to allow clicks
                        modalDiv.remove(); // Don't show buttons for already approved requests
                    } else if (currentStatus === 'rejected') {
                        itemStatus.innerHTML = "<strong>Status: </strong><span style='color: red;'>✗ Rejected</span>";
                        itemStatus.style.color = 'red';
                        item.style.backgroundColor = '#f8d7da';
                        item.style.border = '2px solid #dc3545';
                        item.style.cursor = 'pointer'; // Changed from 'default' to allow clicks
                        modalDiv.remove(); // Don't show buttons for already rejected requests
                    } else {
                        itemStatus.innerHTML = "<strong>Status: </strong><span style='color: orange;'>⏳ Pending</span>";
                        itemStatus.style.color = 'orange';
                        item.style.cursor = 'pointer';
                    }

                    // Add click event listener to show pickup marker (for ALL statuses)
                    item.addEventListener('click', (e) => {
                        console.log('Item clicked! Status:', currentStatus, 'Target:', e.target.tagName);
                        
                        // Prevent action when clicking buttons or input
                        if (e.target.tagName === 'BUTTON' || e.target.tagName === 'INPUT') {
                            return;
                        }
                        
                        console.log('Item clicked, rideMap:', this.rideMap);
                        console.log('From coordinates:', fromLatitude, fromLongitude);
                        
                        // Show pickup location marker on map
                        if (this.rideMap) {
                            console.log('Clearing marker...');
                            this.rideMap.clearMarker('selected');
                            console.log('Adding marker...');
                            this.rideMap.addMarker('selected', fromLatitude, fromLongitude, 'selected');
                            console.log('Setting view...');
                            this.rideMap.setView(fromLatitude, fromLongitude, 15);
                            console.log('Marker operations complete');
                        } else {
                            console.log('No rideMap available');
                        }
                        
                        // Toggle modal (only for pending)
                        if (currentStatus === 'pending') {
                            if (modalDiv.style.display === 'none') {
                                modalDiv.style.display = 'block';
                            } else {
                                modalDiv.style.display = 'none';
                            }
                        }
                    });

                    // Add event listener for accept button (only if pending)
                    if (currentStatus === 'pending') {
                        btnAccept.addEventListener('click', () => {
                            const requestId = data[0][i].id;
                            console.log('Accepting request:', requestId);
                        
                        // Disable buttons during request
                        btnAccept.disabled = true;
                        btnReject.disabled = true;
                        
                        fetch(this.appUrl + '/ride/requests/' + requestId + '/update-status', {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                status: 'approved'
                            })
                        })
                        .then((response) => {
                            if (!response.ok) {
                                throw new Error('Failed to accept request');
                            }
                            return response.json();
                        })
                        .then((result) => {
                            // Log into profit log.
                            const fromSpan = document.getElementById(this.id + '-' + data[0][i].id + '-item-from-span');
                            const toSpan = document.getElementById(this.id + '-' + data[0][i].id + '-item-to-span');
                            
                            fetch(this.appUrl + '/profit/submit', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    ride_id: rideId,
                                    ride_request_id: data[0][i].id,
                                    from_latitude: data[0][i].from_latitude,
                                    from_longitude: data[0][i].from_longitude,
                                    from_address: fromSpan ? fromSpan.innerText : 'Unknown',
                                    to_latitude: data[0][i].to_latitude,
                                    to_longitude: data[0][i].to_longitude,
                                    to_address: toSpan ? toSpan.innerText : 'Unknown',
                                    profit: data[0][i].profit,
                                })
                            }).then((response) => {
                                if(!response.ok){
                                    throw new Error('Failed to submit profit data!');
                                }
                            });

                            console.log('Request accepted:', result);
                            
                            // Update status display
                            itemStatus.innerHTML = "<strong>Status: </strong><span style='color: green;'>✓ Approved</span>";
                            itemStatus.style.color = 'green';
                            
                            // Update item background to show it's been approved
                            item.style.backgroundColor = '#d4edda';
                            item.style.border = '2px solid #28a745';
                            
                            // Hide the modal and buttons
                            modalDiv.style.display = 'none';
                            item.style.cursor = 'default';
                            
                            // Show success message
                            alert('✓ Request approved successfully! The passenger has been notified.');
                        })
                        .catch((error) => {
                            console.error('Error accepting request:', error);
                            alert('Failed to accept request. Please try again.');
                            // Re-enable buttons on error
                            btnAccept.disabled = false;
                            btnReject.disabled = false;
                        });
                    });

                    // Add event listener for reject button
                    btnReject.addEventListener('click', () => {
                        const requestId = data[0][i].id;
                        
                        if (!confirm('Are you sure you want to reject this ride request?')) {
                            return;
                        }
                        
                        console.log('Rejecting request:', requestId);
                        
                        // Disable buttons during request
                        btnAccept.disabled = true;
                        btnReject.disabled = true;
                        
                        fetch(this.appUrl + '/ride/requests/' + requestId + '/update-status', {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                status: 'rejected'
                            })
                        })
                        .then((response) => {
                            if (!response.ok) {
                                throw new Error('Failed to reject request');
                            }
                            return response.json();
                        })
                        .then((result) => {
                            console.log('Request rejected:', result);
                            
                            // Update status display
                            itemStatus.innerHTML = "<strong>Status: </strong><span style='color: red;'>✗ Rejected</span>";
                            itemStatus.style.color = 'red';
                            
                            // Update item background to show it's been rejected
                            item.style.backgroundColor = '#f8d7da';
                            item.style.border = '2px solid #dc3545';
                            
                            // Hide the modal and buttons
                            modalDiv.style.display = 'none';
                            item.style.cursor = 'default';
                            
                            // Show success message
                            alert('Request rejected. The passenger has been notified.');
                        })
                        .catch((error) => {
                            console.error('Error rejecting request:', error);
                            alert('Failed to reject request. Please try again.');
                            // Re-enable buttons on error
                            btnAccept.disabled = false;
                            btnReject.disabled = false;
                        });
                        });
                    }

                this.list.appendChild(item);
            }
        })
        .catch((error) => {
            console.log('No ride requests found or error fetching requests:', error);
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