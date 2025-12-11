@extends('layouts.app')
<x-map-head/>

@push('head')
    @vite(['resources/css/index.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body{
            background: #f9fafb;
            font-family: 'Poppins', sans-serif;
        }
    </style>
@endpush

@section('content')
    <div id="map"></div>

    @auth
        {{-- Show driving mode form if the user account has a driver account --}}
        @if (Auth::user()->isDriver())
            <a href="/ride/create">Create a ride</a>
            <div id="driving-mode">
                {{-- Driving mode button --}}
                <button type="button" id="btn-driving-mode">Start driving mode</button>
                {{-- @TODO: Insert a dropdown menu here to be able to choose a ride to begin with. --}}
                {{-- Use JavaScript to perform the driving mode. --}}

                {{-- Display a selection of available rides within a vehicle. Options within this
                element uses ride ID as a value. --}}
                <select name="driving-mode-option" id="select-driving-vehicle">
                    @foreach (Auth::user()->getRides() as $ride)
                        <option id="ride-option-{{$ride->id}}" value="{{$ride->id}}" data-status="{{$ride->status}}">{{$ride->ride_name}}</option>
                    @endforeach
                </select>
                <div id="driving-mode-infobox"></div>
            </div>
        @endif
    @endauth
    
    <div id="infobox"></div>
    
    <div id="review-box" hidden>
        @auth
            <!-- @NOTE: Shall be hidden by default, but many JavaScript isn't updated yet. -->
            <div id="review-form" hidden>
                <!-- Shall not use form tag but use JavaScript to avoid reloading the page upon posting of review. -->
                <!-- <form action="#" method="POST" id="review-form"> -->
                    <input type="number" name="id" id="review-form-id" hidden>
                    <input type="text" name="description" id="review-form-description" placeholder="Write a review...">
                    <!-- @TODO: Replace into stars -->
                    <select name="rating" id="review-form-rating">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                    <button type="button" id="review-form-submit">Submit</button>
                    <button type="button" id="review-form-edit" hidden>Edit</button>
                    <button type="button" id="review-form-cancel">Cancel</button>
                    <!-- <input type="submit"> -->
                    <!-- <input type="reset"> -->
                <!-- </form> -->
            </div>
            
        @endauth
        <div id="review-list" hidden></div>
    </div>

    @auth
        @if (!Auth::user()->isDriver())
            <div id="ride-selector-wrapper">
                <h2>Available Vehicles</h2>
                <div id="ride-selector">

                </div>
            </div>
            
            <div id="saved-ride-list">

            </div>
        @else
            <div id="passenger-request">
                <!-- <p id="passenger-request-title">Passenger Requests</p>
                
                <select id="passenger-request-ride-selector">

                </select>

                <div id="passenger-request--item">
                    <p id="passenger-request--item-to"></p>
                    <p id="passenger-request--item-from"></p>
                    <p id="passenger-request--item-time"></p>
                    <label for="message">Message (Optional):</label>
                    <input type="text" name="message" id="passenger-request--item-message">
                    <button type="button" id="passenger-request--item-btn-accept"></button>
                    <button type="button" id="passenger-request--item-btn-reject"></button>
                </div> -->
            </div>
        @endif
    @endauth
    
    
    
@endsection

@push('scripts')
    <script type="module">
        import RideMap from '{{ Vite::asset("resources/js/RideMap.js") }}';
        import IndexPage from '{{ Vite::asset("resources/js/IndexPage.js") }}';
        import PassengerRequestList from '{{ Vite::asset("resources/js/Components/PassengerRequestList.js") }}';
        // import SavedRides from '{{ Vite::asset("resources/js/Components/SavedRides.js") }}';

        // @NOTE: Newer code shall encapsulate code into IndexPage instead of throwing up every JS code in this file to reduce the mess.
        var page = new IndexPage('{{env("APP_URL", "")}}');
        var passengerRequest = null;
        @auth
            @if (!Auth::user()->isDriver())
                //var savedRides = new SavedRides('saved-ride-list', '{{env("APP_URL", "")}}');
                page.loadAuthObjects({
                    'saved_rides': 'saved-ride-list',
                });
            @else
                passengerRequest = new PassengerRequestList('passenger-request', '{{env("APP_URL", "")}}', '{{env("NOMINATIM_URL", "")}}', {{Auth::user()->getDriverAccount()->id}});
            @endif
        @endauth

        // Intialize variables
        var infobox = document.getElementById('infobox');
        
        var btnDrivingMode = document.getElementById('btn-driving-mode');
        var drivingModeOption = document.getElementById('select-driving-vehicle');
        var reviewBox = document.getElementById('review-box');

        var reviewForm = document.getElementById('review-form');
        var reviewFormId = document.getElementById('review-form-id');
        var reviewFormDescription = document.getElementById('review-form-description');
        var reviewFormRating = document.getElementById('review-form-rating');
        var reviewFormSubmit = document.getElementById('review-form-submit');
        var reviewFormEdit = document.getElementById('review-form-edit');
        var reviewFormCancel = document.getElementById('review-form-cancel');

        var reviewList = document.getElementById('review-list');
        var selectedDrivingModeOption;
        var status;

        var map = new RideMap('map', '{{env("NOMINATIM_URL", "")}}', '{{env("APP_URL", "")}}', {
            @auth
                'is_auth': true,
                @if (Auth::user()->isDriver())
                    'is_driver': true
                @endif
            @endauth
        });

        map.configureMarkerIcon('default', '{{Vite::asset("resources/img/red_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        map.configureMarkerIcon('currentPos', '{{Vite::asset("resources/img/current_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        map.configureMarkerIcon('active_vehicle', '{{Vite::asset("resources/img/blue_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        map.configureMarkerIcon('inactive_vehicle', '{{Vite::asset("resources/img/grey_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
        map.detectLocation();
        
        // Set up ride marker click handler to show address tooltip
        map.setOnRideMarkerClick((e, destination) => {
            // Close any existing tooltips
            map.cachedMarkers.eachLayer((layer) => {
                if (layer.getTooltip()) {
                    layer.closeTooltip();
                    layer.unbindTooltip();
                }
            });

            // Fetch and display address above the marker
            fetch("{{env('NOMINATIM_URL', '')}}/reverse?lat=" + destination.latitude + "&lon=" + destination.longitude + '&format=json&zoom=18&addressdetails=1')
                .then(response => response.json())
                .then(data => {
                    e.target.bindTooltip(data.display_name, {
                        permanent: true,
                        direction: 'top',
                        className: 'destination-tooltip'
                    }).openTooltip();
                })
                .catch(error => {
                    console.log("Error: " + error);
                    e.target.bindTooltip('Location', {
                        permanent: true,
                        direction: 'top'
                    }).openTooltip();
                });
        });
        // Functionality for viewing vehicle information
        map.setOnVehicleMarkerClick((e, data) => {

            // @NOTE: You may use any other elements other than infobox to display the ride-popup.
            infobox.innerHTML =
                '<div id="ride-popup"><button type="button" id="ride-popup-close-btn">Close</button><br>' + 
                "<p><strong>"+data.vehicle_name+"</strong></p>" + 
                '<p id="ride-location">Retrieving location...</p>' +
                "<p><strong>Status:</strong>" + data.status + "</p>" +
                "<p>"+data.latitude+", "+data.longitude+"</p>" + 
                '<button type="button" id="ride-view-review-btn">View Reviews</button><br>';
            infobox.style.display = "block";
            infobox.innerHTML += '<strong>Available rides: </strong><select id="ride-list" name="ride-list"></select>';
            @auth
                @if (!(Auth::user()->isDriver()))
                    infobox.innerHTML += '<br><a id="btn-make-ride-request">Make Ride Request</button></div>';
                @endif
            @endauth
            
            // Shows or hides Ride Request button.
            // document.getElementById('btn-make-ride-request').addEventListener('click', () => {
            //     document.getElementById('ride-request').hidden = !document.getElementById('ride-request').hidden;
            // });

            var rideList = document.getElementById('ride-list');
            
            // Set up ride-popup-close-btn
            // Tag: onCloseRide
            infobox.addEventListener('click', (e) => {
                if (e.target && e.target.id === 'ride-popup-close-btn') {
                    infobox.innerHTML = "";
                    infobox.style.display = "none";
                    map.cachedMarkers.clearLayers();
                    reviewBox.hidden = true;
                }
            });

            reviewBox.hidden = false;

            // Obtain the human-readable address of latitude and longitude data.
            // Tag: onMarkerClick-onReverseGeocode
            map.reverseGeocode(data.latitude, data.longitude).then((location) => {
                document.getElementById('ride-location').innerHTML = location.display_name;
            });
                
            // Gets all the associated rides of a vehicle and display a selection of it.
            // Tag: onMarkerClick-onRetrieveRides
            map.retrieveRides(data.id)().then((data) => {
                var count = Object.keys(data.rides).length;

                rideList.innerHTML = "";

                for(let i = -1; i < count; i++){
                    var option = document.createElement("option");
                    if(i == -1){
                        option.setAttribute("disabled", true);
                        option.setAttribute("selected", true);
                        option.innerHTML = "---";
                    }else{
                        option.setAttribute("id", "ride-option-"+data.rides[i].id);
                        option.setAttribute("value", data.rides[i].id);
                        option.innerHTML = data.rides[i].ride_name;
                    }

                    rideList.appendChild(option);
                }

                var viewReviewsBtn = document.getElementById('ride-view-review-btn');

                // Tag: onRideChange or onRideSelect
                rideList.addEventListener('change', () => {
                    // Hide/show ride-view-review-btn based on selected ride.
                    let rideId = rideList.value;
                    if(rideList.value < 1){
                        viewReviewsBtn.hidden = true;
                    }else{
                        // Change the action route to reflect the ride ID.
                        // var reviewForm = document.getElementById('review-form');
                        // reviewForm.setAttribute('action', '{{env("APP_URL", "")}}' + '/ride/' + rideList.value + '/reviews/submit');

                        // var reviewRideIdField = document.getElementById('review-ride-id');
                        // reviewRideIdField.value = rideList.value;

                        @auth
                            @if (Auth::user()->isDriver())
                                passengerRequest.destroyItems();
                                passengerRequest.displayItems(rideId);
                            @endif
                        @endauth

                        reviewForm.hidden = false;
                        reviewFormSubmit.hidden = false;
                        reviewFormEdit.hidden = true;
                        viewReviewsBtn.hidden = false;
                    }

                    var btnMakeRideRequest = document.getElementById('btn-make-ride-request');
                    if(btnMakeRideRequest){
                        btnMakeRideRequest.setAttribute('href', '/ride/'+rideId+'/requests/create');
                    }

                    // Once the selection from ride list has changed, display all of its associated ride destinations.
                    getRides(rideList.value); 
                });

                reviewFormSubmit.addEventListener('click', () => {
                    fetch('{{env("APP_URL", "")}}' + '/ride/' + rideList.value + '/reviews/submit', {
                        method: "POST",
                        body: JSON.stringify({
                            description: document.getElementById('review-form-description').value,
                            rating: parseInt(document.getElementById('review-form-rating').value)
                        }),
                        headers: {
                            "Content-type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-Token": document.querySelector('meta[name=csrf-token]').content,
                        },
                    }).then((response) => {
                        return response.json();
                    }).then((data) => {
                        document.getElementById('review-form-description').value = "";
                    }).catch((error) => {
                        throw new Error(error);
                    });
                });

                // Set up ride-view-review-btn
                viewReviewsBtn.addEventListener('click', () => {
                    // Display review list.
                    fetch('{{env("APP_URL", "")}}' + '/ride/' + rideList.value + '/reviews')
                    .then((response) => {
                        return response.json();
                    })
                    .then((data) => {
                        // console.log(data);

                        reviewList.hidden = false;
                        // Display all reviews
                        // Tag: onRideChange or onRideSelect -> onDisplayReviews
                        // @TODO: This shall allow the user from being able to automatically clear up the review list
                        // @TODO: Use pagination to avoid loading all of the items. This improvement shall take place once APIs are available.
                        // upon navigating to other rides.
                        var reviewCount = Object.keys(data.reviews).length;
                        for(let i = 0; i < reviewCount; i++){
                            console.log(data.reviews[i]);

                            var reviewItem = document.createElement('div');
                            // @TODO: Enclose this with div tag.
                            var reviewDescription = document.createElement('p');
                            reviewDescription.innerHTML = data.reviews[i].description;
                            reviewItem.appendChild(reviewDescription);
                            // @TODO: Add ratings and created_at fields.

                            // @NOTE: Known issue: event listeners are duplicated.
                            // Implement edit form per review.
                            @auth
                                if({{Auth::user()->id}} == data.reviews[i].user_id){
                                    // Shall be displayed on a menu.
                                    var reviewTagId = 'edit-review-btn-'+data.reviews[i].id;
                                    // reviewList.innerHTML += '<button id="'+reviewTagId+'">Edit</button>';

                                    var editReviewBtn = document.createElement('button');
                                    editReviewBtn.setAttribute('id', reviewTagId);
                                    editReviewBtn.innerHTML = 'Edit';
                                    editReviewBtn.addEventListener('click', () => {
                                        reviewForm.hidden = false;

                                        // @NOTE: Instead of using existing review creation form, it may be better to make a separate one that
                                        // appears within the div of a review item.
                                        reviewFormId.value = data.reviews[i].id;
                                        reviewFormDescription.value = data.reviews[i].description;
                                        console.log(reviewFormDescription.value);
                                        reviewFormRating.value = data.reviews[i].rating;
                                        reviewFormSubmit.hidden = true;
                                        reviewFormEdit.value = data.reviews[i].rating;
                                        reviewFormEdit.hidden = false;

                                        reviewFormEdit.addEventListener('click', () => {
                                            page.onUpdateReview(data.reviews[i].id);
                                        });
                                    });

                                    reviewItem.appendChild(editReviewBtn);

                                    var deleteReviewBtn = document.createElement('button');
                                    deleteReviewBtn.setAttribute('id', 'delete-review-btn-'+data.reviews[i].id);
                                    deleteReviewBtn.innerHTML = 'Delete';
                                    deleteReviewBtn.addEventListener('click', () => {
                                        page.onDeleteReview(data.reviews[i].id);
                                    });

                                    reviewItem.appendChild(deleteReviewBtn);
                                }
                            @endauth
                            
                            reviewList.appendChild(reviewItem);
                        }

                    }).catch((error) => {
                        throw new Error(error);
                    });
                });

                @auth
                    // If the user is not a driver and does not own the ride, then add the ability to make reviews for each ride.
                    @if (!(Auth::user()->isDriver()))
                        reviewList.hidden = false;
                        // getRides(rideList.value);
                    @else
                        reviewList.hidden = true;
                        // infobox.innerHTML += "</div>";
                    @endif
                @endauth
                
            });
        });

        // map.enablePanToRetrieveAllRideMarkers();
        map.enablePanToRetrieveVehicles();
        
        @auth
            @if (Auth::user()->isDriver())

                // Make the driving mode button always update its toggle value after loading the page.
                updateSelectedRideOption();

                // Toggle the text of driving mode button once the user changes selection from the ride list.
                drivingModeOption.addEventListener('change', function(){
                    updateSelectedRideOption();
                });

                // Handle driving mode.
                btnDrivingMode.addEventListener('click', function(){
                    // Initialize variables.
                    var drivingMode = "inactive";                    
                    selectedDrivingModeOption = document.getElementById("ride-option" + "-" + drivingModeOption.value);

                    // Toggle the text of driving mode button after clicking on the button itself.
                    if(status == "active"){
                        drivingMode = "inactive";
                        selectedDrivingModeOption.setAttribute('data-status', 'inactive');
                    }else{
                        drivingMode = "active";
                        selectedDrivingModeOption.setAttribute('data-status', 'active');
                    }
                    updateSelectedRideOption();

                    // console.log("Mode: "+ drivingMode);
                    
                    // Update both ride and vehicle driving mode status and marker icon color.
                    // ========================================================================

                    // Update ride status
                    fetch('{{env("APP_URL", "")}}' + '/ride/'+drivingModeOption.value+'/update-status', {
                        method: "PATCH",
                        body: JSON.stringify({
                            status: drivingMode,
                        }),
                        headers: {
                            "Content-type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-Token": document.querySelector('meta[name=csrf-token]').content,
                        },
                    })
                    .then((response) => {
                        return response.json();
                    }).then((data) => {

                        // Update vehicle status
                        fetch('{{env("APP_URL", "")}}' + '/vehicle/'+data.ride.vehicle_id+'/update-status', {
                            method: "PATCH",
                            body: JSON.stringify({
                                status: drivingMode,
                            }),
                            headers: {
                                "Content-type": "application/json",
                                "Accept": "application/json",
                                "X-CSRF-Token": document.querySelector('meta[name=csrf-token]').content,
                            },
                        })
                        .then((response) => {
                            return response.json();
                        }).then((vehicleData) => {
                            // console.log(vehicleData);

                            // console.log("Vehicle ID: " + vehicleData.vehicle.id);

                            // Toggle live tracking. This updates the current marker position (green) into the current ones.
                            if(drivingMode == "active"){
                                map.startLiveTracking(vehicleData.vehicle.id);
                                // console.log("Tracking ID: " + map.trackingId);
                            }else{
                                map.stopLiveTracking(map.trackingId);
                                // console.log("Tracking ID Stopped: " + map.trackingId);
                            }

                            //CHange vehicle icon color
                            map.setMarkerIcon("vehicle-" + vehicleData.vehicle.id, status + "_vehicle");

                        }).catch((error) => {
                            throw new Error(error);
                        });
                    }).catch((error) => {
                        throw new Error(error);
                    });
                });
            @endif

            // Handle driving mode select list.
            function updateSelectedRideOption(){
                //Retrieves data needed to be processed.
                var selectedDrivingModeOption = document.getElementById("ride-option" + "-" + drivingModeOption.value);
                var infobox = document.getElementById("driving-mode-infobox");
                status = selectedDrivingModeOption.getAttribute('data-status');

                // Toggles button state.
                if(status == "active"){
                    btnDrivingMode.innerHTML = "Stop driving mode";
                }else{
                    btnDrivingMode.innerHTML = "Start driving mode";
                }
                
                getRides(drivingModeOption.value);

                // Zoom into the position of associated vehicle from a selected ride.
                fetch('{{env("APP_URL", "")}}' + '/ride/'+drivingModeOption.value)
                .then((response) => {
                    return response.json();
                }).then((data) => {

                    fetch('{{env("APP_URL", "")}}' + '/api/vehicle/'+data.ride.vehicle_id)
                    .then((response) => {
                        console.log("URL: " + '{{env("APP_URL", "")}}' + '/vehicle/'+data.ride.vehicle_id);
                        return response.json();
                    }).then((vehicleData) => {
                        // Displays information into driving-mode-infobox.
                        infobox.innerHTML = "<p>"+vehicleData.vehicle.vehicle_name+"</p>"

                        map.getMap().setView([vehicleData.vehicle.latitude, vehicleData.vehicle.longitude], 16);
                    }).catch((error) => {
                        throw new Error(error);
                    });

                }).catch((error) => {
                    throw new Error(error);
                });
            }

        @endauth

        
        // Retrieves ride destinations based on ride ID.
        function getRides(rideId){
            map.retrieveRideMarkers(rideId, true, true)();
        }
        
    </script>
@endpush