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

        // var rideSelector = document.createElement('select');
        // rideSelector.setAttribute('id', this.id + '-ride-selector');
        // this.element.appendChild(rideSelector);
        
        // fetch(this.appUrl + '/driver/'+this.driverId+'/ride/')
        // .then((response) => {
        //     return response.json();
        // })
        // .then((data) => {

        //     console.log(data);

        //     let count = Object.keys(data).length;

        //     for(let i = 0; i < count; i++){    
        //         var rideSelectorItem = document.createElement('option');
        //         rideSelectorItem.value = data[0][i].id;
        //         rideSelectorItem.innerHTML = data[0][i].ride_name;

        //         rideSelector.appendChild(rideSelectorItem);
        //     }
        // })
        // .catch((error) => {
        //     throw new Error(error);
        // });

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
                // Wrap in IIFE to ensure proper closure for each iteration
                ((requestData, index) => {
                    console.log(requestData);
                    
                    // Capture ALL data for this iteration
                    const requestId = requestData.id;
                    const toLatitude = requestData.to_latitude;
                    const toLongitude = requestData.to_longitude;
                    const fromLatitude = requestData.from_latitude;
                    const fromLongitude = requestData.from_longitude;
                    const requestTime = requestData.time;
                    const requestPrice = requestData.price;
                    const requestProfit = requestData.profit;
                    const currentStatus = requestData.status;
                    
                    var item = document.createElement('div');
                    item.setAttribute('id', this.id + '-' + requestId + '-item');
                    item.className = 'ride-request-item';

                    var itemTo = document.createElement('p');
                    
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
                    itemTime.setAttribute('id', this.id + '-' + requestId + '-item-time');
                    itemTime.innerHTML = "Time: " + requestTime;
                    item.appendChild(itemTime);

                    var itemProfit = document.createElement('p');
                    itemProfit.setAttribute('id', this.id + '-' + requestId + '-item-profit');
                    itemProfit.innerHTML = "Estimated Profit: PHP" + requestPrice;
                    item.appendChild(itemProfit);

                    var itemStatus = document.createElement('p');
                    itemStatus.setAttribute('id', this.id + '-' + requestId + '-item-status');
                    itemStatus.innerHTML = "<strong>Status: </strong>" + currentStatus;
                    itemStatus.style.fontWeight = 'bold';
                    item.appendChild(itemStatus);

                    // Modal div for message input and action buttons
                    var modalDiv = document.createElement('div');
                    modalDiv.setAttribute('id', this.id + '-' + requestId + '-item-modal');
                    modalDiv.className = 'ride-request-modal';

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
                    btnAccept.setAttribute('id', this.id + '-' + requestId + '-item-btn-accept');
                    btnAccept.className = 'btn-accept';
                    btnAccept.innerHTML = "Accept";
                    modalDiv.appendChild(btnAccept);

                    var btnReject = document.createElement('button');
                    btnReject.setAttribute('type', 'button');
                    btnReject.setAttribute('id', this.id + '-' + requestId + '-item-btn-reject');
                    btnReject.className = 'btn-reject';
                    btnReject.innerHTML = "Reject";
                    modalDiv.appendChild(btnReject);

                    item.appendChild(modalDiv);

                    // Create delete button (visible for approved/rejected/cancelled, hidden for pending)
                    var btnDelete = document.createElement('button');
                    btnDelete.setAttribute('type', 'button');
                    btnDelete.setAttribute('id', this.id + '-' + requestId + '-item-btn-delete');
                    btnDelete.className = 'btn-delete';
                    btnDelete.innerHTML = "Delete";
                    item.appendChild(btnDelete);

                    // Apply styling based on current status
                    console.log('Creating item with status:', currentStatus, 'ID:', requestId);
                    
                    if (currentStatus === 'approved') {
                        itemStatus.innerHTML = "<strong>Status: </strong><span class='status-approved-text'>✓ Approved</span>";
                        item.classList.add('status-approved');
                        item.style.cursor = 'pointer';
                        // Keep modalDiv in DOM but ensure it stays hidden
                        modalDiv.style.display = 'none';
                        btnDelete.style.display = 'block'; // Show delete button
                    } else if (currentStatus === 'rejected') {
                        itemStatus.innerHTML = "<strong>Status: </strong><span class='status-rejected-text'>✗ Rejected</span>";
                        item.classList.add('status-rejected');
                        item.style.cursor = 'pointer';
                        // Keep modalDiv in DOM but ensure it stays hidden
                        modalDiv.style.display = 'none';
                        btnDelete.style.display = 'block'; // Show delete button
                    } else if (currentStatus === 'cancelled') {
                        itemStatus.innerHTML = "<strong>Status: </strong><span class='status-cancelled-text'>✗ Cancelled</span>";
                        item.classList.add('status-cancelled');
                        item.style.cursor = 'pointer';
                        // Keep modalDiv in DOM but ensure it stays hidden
                        modalDiv.style.display = 'none';
                        btnDelete.style.display = 'block'; // Show delete button
                    } else if (currentStatus === 'pending') {
                        itemStatus.innerHTML = "<strong>Status: </strong><span class='status-pending-text'>⏳ Pending</span>";
                        item.classList.add('status-pending');
                        item.style.cursor = 'pointer';
                        btnDelete.style.display = 'none'; // Hide delete button for pending
                    } else {
                        // Default styling for unknown status
                        item.style.cursor = 'pointer';
                        btnDelete.style.display = 'none';
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
                        console.log('To coordinates:', toLatitude, toLongitude);
                        
                        // Show pickup and destination markers on map
                        if (this.rideMap) {
                            console.log('Clearing markers...');
                            this.rideMap.clearMarker('selected');
                            this.rideMap.clearMarker('selected2');
                            console.log('Adding pickup marker...');
                            this.rideMap.addMarker('selected', fromLatitude, fromLongitude, 'selected');
                            console.log('Adding destination marker...');
                            this.rideMap.addMarker('selected2', toLatitude, toLongitude, 'selected2');
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
                            console.log('Request approved, submitting profit log...');
                            
                            // Log into profit log.
                            const fromSpan = document.getElementById(this.id + '-' + requestId + '-item-from-span');
                            const toSpan = document.getElementById(this.id + '-' + requestId + '-item-to-span');
                            
                            const profitData = {
                                ride_id: rideId,
                                ride_request_id: requestId,
                                from_latitude: fromLatitude,
                                from_longitude: fromLongitude,
                                from_address: fromSpan ? fromSpan.innerText : 'Unknown',
                                to_latitude: toLatitude,
                                to_longitude: toLongitude,
                                to_address: toSpan ? toSpan.innerText : 'Unknown',
                                profit: requestPrice,
                            };
                            
                            console.log('Profit data to submit:', profitData);
                            
                            // fetch(this.appUrl + '/profit/submit', {
                            //     method: 'POST',
                            //     headers: {
                            //         'Content-Type': 'application/json',
                            //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            //     },
                            //     body: JSON.stringify(profitData)
                            // })
                            // .then((response) => {
                            //     console.log('Profit submission response status:', response.status);
                            //     return response.text().then(text => {
                            //         console.log('Response text:', text);
                            //         if(!response.ok){
                            //             console.error('Profit submission failed:', text);
                            //             throw new Error('Failed to submit profit data: ' + text);
                            //         }
                            //         try {
                            //             return JSON.parse(text);
                            //         } catch(e) {
                            //             console.error('Failed to parse JSON:', e);
                            //             console.error('Response was:', text);
                            //             throw new Error('Invalid JSON response: ' + text.substring(0, 100));
                            //         }
                            //     });
                            // })
                            // .then((profitResult) => {
                            //     console.log('Profit log saved successfully:', profitResult);
                            // })
                            // .catch((error) => {
                            //     console.error('Error submitting profit:', error);
                            // });

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
                            
                            // Show delete button after rejection
                            btnDelete.style.display = 'block';
                            
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

                    // Add event listener for delete button (for all statuses)
                    btnDelete.addEventListener('click', () => {
                        if (!confirm('Are you sure you want to delete this ride request? This action cannot be undone.')) {
                            return;
                        }
                        
                        console.log('Deleting request:', requestId);
                        btnDelete.disabled = true;
                        
                        fetch(this.appUrl + '/ride/requests/' + requestId + '/delete', {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then((response) => {
                            if (!response.ok) {
                                throw new Error('Failed to delete request');
                            }
                            return response.json();
                        })
                        .then((result) => {
                            console.log('Request deleted:', result);
                            
                            // Animate removal
                            item.style.transition = 'opacity 0.3s';
                            item.style.opacity = '0';
                            setTimeout(() => {
                                item.remove();
                            }, 300);
                            
                            alert('✓ Request deleted successfully.');
                        })
                        .catch((error) => {
                            console.error('Error deleting request:', error);
                            alert('Failed to delete request. Please try again.');
                            btnDelete.disabled = false;
                        });
                    });

                this.list.appendChild(item);
                })(data[0][i], i); // Close IIFE and pass data
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