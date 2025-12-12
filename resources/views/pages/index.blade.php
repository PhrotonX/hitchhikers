@extends('layouts.app')
<x-map-head/>

@push('head')
    @vite(['resources/css/index.css'])
    @vite(['resources/css/ride_request/item.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
@endpush

@section('content')
    <!-- MAIN LAYOUT -->
    <div class="main-layout container">
        <aside class="mlay-side">
            <!-- Left side placeholder (keeps layout consistent) -->
        </aside>

        <main class="main-content">
            <!-- <section class="top-section">
                <h4>Share Your Journey, Save Your Costs</h4>
                <p>Find rides, connect with drivers, and travel affordably.</p>
            </section> -->

            <!-- Search Bar -->
            <section class="search-section" style="margin-top:16px">
                <div class="search-container">
                    <input type="text" id="ride-search-input" placeholder="Search destinations..." />
                    <button type="button" id="ride-search-btn">Search</button>
                </div>
                <div id="search-results" style="display: none;"></div>
            </section>

            <!-- Map area from existing app (preserved) -->
            <section style="margin-top:16px">
                <div id="map"></div>
            </section>

            <!-- <section class="mid-section" style="margin-top:16px">
                <h3>Mid Section</h3>
            </section>

            <section class="bottom-section" style="margin-top:16px">
                <h3>Bottom Section</h3>
            </section> -->

            <!-- Backend-driven UI blocks (reviews, driving mode, requests) preserved below -->
            @auth
                {{-- Show driving mode form if the user account has a driver account --}}
                @if (Auth::user()->isDriver())
                    <a href="/ride/create">Create a ride</a>
                    <div id="driving-mode">
                        <button type="button" id="btn-driving-mode">Start driving mode</button>

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
                    <div id="review-form" hidden>
                        <input type="number" name="id" id="review-form-id" hidden>
                        <input type="text" name="description" id="review-form-description" placeholder="Write a review...">
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
                    </div>
                @endauth
                <div id="review-list" hidden></div>

                
            </div>

            @auth
                @if (!Auth::user()->isDriver())
                    <div id="ride-selector-wrapper">
                        <h2>Available Rides</h2>
                        <div id="ride-selector"></div>
                    </div>

                    <div id="saved-ride-list"></div>
                @else
                    <div id="passenger-request"></div>
                @endif
            @endauth
        </main>
    </div>

   

@endsection

@push('scripts')
    <script type="module">
        import RideMap from '{{ Vite::asset("resources/js/RideMap.js") }}';
        import IndexPage from '{{ Vite::asset("resources/js/IndexPage.js") }}';
        import PassengerRequestList from '{{ Vite::asset("resources/js/Components/PassengerRequestList.js") }}';

        // Delay initialization until window load to ensure Leaflet and other assets are ready
        var page = new IndexPage('{{env("APP_URL", "")}}');
        window.addEventListener('load', function(){
            try {
                var passengerRequest = null;
                @auth
                    @if (!Auth::user()->isDriver())
                        //var savedRides = new SavedRides('saved-ride-list', '{{env("APP_URL", "")}}');
                        page.loadAuthObjects({
                            'saved_rides': 'saved-ride-list',
                        });
                    @endif
                @endauth

                // Intialize variables
                var infobox = document.getElementById('infobox');
                var btnDrivingMode = document.getElementById('btn-driving-mode') || null;
                var drivingModeOption = document.getElementById('select-driving-vehicle') || null;
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
                map.configureMarkerIcon('selected', '{{Vite::asset("resources/img/selected_pin.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
                map.configureMarkerIcon('selected2', '{{Vite::asset("resources/img/selected_pin_2.png")}}', '{{Vite::asset("resources/img/shadow_pin.png")}}');
                map.detectLocation();

                // Search functionality
                var searchInput = document.getElementById('ride-search-input');
                var searchBtn = document.getElementById('ride-search-btn');
                var searchResults = document.getElementById('search-results');

                function performSearch() {
                    var query = searchInput.value.trim();
                    if (!query) {
                        searchResults.style.display = 'none';
                        return;
                    }

                    fetch('{{env("APP_URL", "")}}' + '/ride/search?ride_address=' + encodeURIComponent(query))
                        .then(response => response.json())
                        .then(data => {
                            searchResults.innerHTML = '';
                            
                            if (Object.keys(data.vehicles).length === 0) {
                                searchResults.innerHTML = '<p>No results found</p>';
                                searchResults.style.display = 'block';
                                return;
                            }

                            var resultsHtml = '<h3>Search Results</h3>';
                            
                            Object.values(data.vehicles).forEach(vehicle => {
                                var vehicleRides = Object.values(data.rides).filter(r => r.vehicle_id === vehicle.id);
                                var rideNames = vehicleRides.map(r => r.ride_name).join(', ');
                                
                                resultsHtml += `
                                    <div class="search-result-item" data-vehicle-id="${vehicle.id}" style="cursor: pointer; padding: 10px; border: 1px solid #ccc; margin: 5px 0;">
                                        <strong>${vehicle.vehicle_name}</strong><br>
                                        <span>Rides: ${rideNames}</span><br>
                                        <span>Status: ${vehicle.status}</span>
                                    </div>
                                `;
                            });
                            
                            searchResults.innerHTML = resultsHtml;
                            searchResults.style.display = 'block';

                            // Add click handlers to search results
                            document.querySelectorAll('.search-result-item').forEach(item => {
                                item.addEventListener('click', function() {
                                    var vehicleId = this.getAttribute('data-vehicle-id');
                                    var vehicle = data.vehicles[vehicleId];
                                    
                                    // Simulate vehicle marker click
                                    if (vehicle) {
                                        // Clear search
                                        searchInput.value = '';
                                        searchResults.style.display = 'none';
                                        
                                        // Pan to vehicle location
                                        map.getMap().setView([vehicle.latitude, vehicle.longitude], 15);
                                        
                                        // Trigger the vehicle marker click handler
                                        var vehicleMarkerClickHandler = map.getOnVehicleMarkerClick();
                                        if (vehicleMarkerClickHandler) {
                                            vehicleMarkerClickHandler({ target: null }, vehicle);
                                        }
                                    }
                                });
                            });
                        })
                        .catch(error => {
                            console.error('Search error:', error);
                            searchResults.innerHTML = '<p>Error performing search</p>';
                            searchResults.style.display = 'block';
                        });
                }

                if (searchBtn) {
                    searchBtn.addEventListener('click', performSearch);
                }
                
                if (searchInput) {
                    searchInput.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            performSearch();
                        }
                    });
                }
                
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
                    if (!infobox) return; // Guard against missing infobox
                    infobox.innerHTML =
                        '<div id="ride-popup"><button type="button" id="ride-popup-close-btn">Close</button><br>' + 
                        "<p><strong>"+data.vehicle_name+"</strong></p>" + 
                        '<p id="ride-location">Retrieving location...</p>' +
                        "<p><strong>Status:</strong>" + data.status + "</p>" +
                        "<p>"+data.latitude+", "+data.longitude+"</p>" + 
                        '<button type="button" id="ride-view-review-btn">View Reviews</button><br>';
                    infobox.style.display = "block";
                    infobox.innerHTML += '<strong>Available rides: </strong><select id="ride-list" name="ride-list"></select>';
                    infobox.innerHTML += '<br><button type="button" id="ride-view-details-btn" style="margin-top: 10px; display: none;">View Ride Details</button>';
                    @auth
                        @if (!(Auth::user()->isDriver()))
                            infobox.innerHTML += '<br><a id="btn-make-ride-request">Make Ride Request</a>';
                        @endif
                    @endauth
                    infobox.innerHTML += '</div>';
                    
                    var rideList = document.getElementById('ride-list');
                    if (infobox && rideList) {
                        infobox.addEventListener('click', (e) => {
                            if (e.target && e.target.id === 'ride-popup-close-btn') {
                                infobox.innerHTML = "";
                                infobox.style.display = "none";
                                map.cachedMarkers.clearLayers();
                                if (reviewBox) reviewBox.hidden = true;
                            }
                        });
                    }

                    if (reviewBox) reviewBox.hidden = false;

                    map.reverseGeocode(data.latitude, data.longitude).then((location) => {
                        var rideLocation = document.getElementById('ride-location');
                        if (rideLocation) rideLocation.innerHTML = location.display_name;
                    });

                    map.retrieveRides(data.id)().then((data) => {
                        if (!rideList) return;
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
                        var viewDetailsBtn = document.getElementById('ride-view-details-btn');

                        if (rideList) rideList.addEventListener('change', () => {
                            let rideId = rideList.value;
                            if(rideList.value < 1){
                                if (viewReviewsBtn) viewReviewsBtn.hidden = true;
                                if (viewDetailsBtn) viewDetailsBtn.style.display = 'none';
                            }else{
                                @auth
                                    @if (Auth::user()->isDriver())
                                        if (passengerRequest) {
                                            passengerRequest.destroyItems();
                                            passengerRequest.displayItems(rideId);
                                        }
                                    @endif
                                @endauth

                                if (reviewForm) reviewForm.hidden = false;
                                if (reviewFormSubmit) reviewFormSubmit.hidden = false;
                                if (reviewFormEdit) reviewFormEdit.hidden = true;
                                if (viewReviewsBtn) viewReviewsBtn.hidden = false;
                                if (viewDetailsBtn) viewDetailsBtn.style.display = 'inline-block';
                            }

                            var btnMakeRideRequest = document.getElementById('btn-make-ride-request');
                            if(btnMakeRideRequest){
                                btnMakeRideRequest.setAttribute('href', '/ride/'+rideId+'/requests/create');
                            }

                            getRides(rideList.value); 
                        });

                        // Handle View Ride Details button click
                        viewDetailsBtn.addEventListener('click', () => {
                            window.location.href = '{{env("APP_URL", "")}}' + '/ride/' + rideList.value;
                        });

                        if (reviewFormSubmit) reviewFormSubmit.addEventListener('click', () => {
                            fetch('{{env("APP_URL", "")}}' + '/ride/' + rideList.value + '/reviews/submit', {
                                method: "POST",
                                body: JSON.stringify({
                                    description: reviewFormDescription ? reviewFormDescription.value : "",
                                    rating: reviewFormRating ? parseInt(reviewFormRating.value) : 0
                                }),
                                headers: {
                                    "Content-type": "application/json",
                                    "Accept": "application/json",
                                    "X-CSRF-Token": document.querySelector('meta[name=csrf-token]').content,
                                },
                            }).then((response) => {
                                return response.json();
                            }).then((data) => {
                                if (reviewFormDescription) reviewFormDescription.value = "";
                            }).catch((error) => {
                                console.error("Review submit error:", error);
                            });
                        });

                        if (viewReviewsBtn) viewReviewsBtn.addEventListener('click', () => {
                            fetch('{{env("APP_URL", "")}}' + '/ride/' + rideList.value + '/reviews')
                            .then((response) => { return response.json(); })
                            .then((data) => {
                                if (reviewList) reviewList.hidden = false;
                                var reviewCount = Object.keys(data.reviews).length;
                                for(let i = 0; i < reviewCount; i++){
                                    var reviewItem = document.createElement('div');
                                    var reviewDescription = document.createElement('p');
                                    reviewDescription.innerHTML = data.reviews[i].description;
                                    reviewItem.appendChild(reviewDescription);

                                    @auth
                                        if({{Auth::user()->id}} == data.reviews[i].user_id){
                                            var reviewTagId = 'edit-review-btn-'+data.reviews[i].id;
                                            var editReviewBtn = document.createElement('button');
                                            editReviewBtn.setAttribute('id', reviewTagId);
                                            editReviewBtn.innerHTML = 'Edit';
                                            editReviewBtn.addEventListener('click', () => {
                                                if (reviewForm) reviewForm.hidden = false;
                                                if (reviewFormId) reviewFormId.value = data.reviews[i].id;
                                                if (reviewFormDescription) reviewFormDescription.value = data.reviews[i].description;
                                                if (reviewFormRating) reviewFormRating.value = data.reviews[i].rating;
                                                if (reviewFormSubmit) reviewFormSubmit.hidden = true;
                                                if (reviewFormEdit) {
                                                    reviewFormEdit.value = data.reviews[i].rating;
                                                    reviewFormEdit.hidden = false;
                                                    reviewFormEdit.addEventListener('click', () => { if (page) page.onUpdateReview(data.reviews[i].id); });
                                                }
                                            });
                                            reviewItem.appendChild(editReviewBtn);

                                            var deleteReviewBtn = document.createElement('button');
                                            deleteReviewBtn.setAttribute('id', 'delete-review-btn-'+data.reviews[i].id);
                                            deleteReviewBtn.innerHTML = 'Delete';
                                            deleteReviewBtn.addEventListener('click', () => { page.onDeleteReview(data.reviews[i].id); });
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
                            @else
                                reviewList.hidden = true;
                            @endif
                        @endauth
                        
                    });
                });

                map.enablePanToRetrieveVehicles();
                
                @auth
                    @if (Auth::user()->isDriver())
                        // Initialize PassengerRequestList with map reference
                        passengerRequest = new PassengerRequestList('passenger-request', '{{env("APP_URL", "")}}', '{{env("NOMINATIM_URL", "")}}', {{Auth::user()->getDriverAccount()->id}}, map);

                        // Set up event listener for View Ride Details button (if exists)
                        var driverViewDetailsBtn = document.getElementById('driver-view-details-btn');
                        if (driverViewDetailsBtn) {
                            driverViewDetailsBtn.addEventListener('click', () => {
                                window.location.href = '{{env("APP_URL", "")}}' + '/ride/' + drivingModeOption.value;
                            });
                        }

                        // Toggle the text of driving mode button once the user changes selection from the ride list.
                        if (drivingModeOption) {
                            drivingModeOption.addEventListener('change', function(){
                                updateSelectedRideOption();
                            });
                        }

                        // Handle driving mode.
                        if (btnDrivingMode) {
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

                                // Update both ride and vehicle driving mode status and marker icon color.
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
                                        // Toggle live tracking. This updates the current marker position (green) into the current ones.
                                        if(drivingMode == "active"){
                                            map.startLiveTracking(vehicleData.vehicle.id);
                                        }else{
                                            map.stopLiveTracking(map.trackingId);
                                        }

                                        //Change vehicle icon color
                                        map.setMarkerIcon("vehicle-" + vehicleData.vehicle.id, status + "_vehicle");

                                    }).catch((error) => {
                                        throw new Error(error);
                                    });
                                }).catch((error) => {
                                    throw new Error(error);
                                });
                            });
                        }

                        // Handle driving mode select list.
                        function updateSelectedRideOption(){
                            if (!drivingModeOption) return;
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
                            
                            // Display passenger requests for this ride
                            passengerRequest.destroyItems();
                            passengerRequest.displayItems(drivingModeOption.value);

                            // Zoom into the position of associated vehicle from a selected ride.
                            fetch('{{env("APP_URL", "")}}' + '/ride/'+drivingModeOption.value)
                            .then((response) => {
                                return response.json();
                            }).then((data) => {

                                fetch('{{env("APP_URL", "")}}' + '/api/vehicle/'+data.ride.vehicle_id)
                                .then((response) => {
                                    return response.json();
                                }).then((vehicleData) => {
                                    // Displays information into driving-mode-infobox.
                                    infobox.innerHTML = "<p>"+vehicleData.vehicle.vehicle_name+"</p>";

                                    map.getMap().setView([vehicleData.vehicle.latitude, vehicleData.vehicle.longitude], 16);
                                }).catch((error) => {
                                    throw new Error(error);
                                });

                            }).catch((error) => {
                                throw new Error(error);
                            });
                        }

                        // Initialize on load
                        if (drivingModeOption) updateSelectedRideOption();
                    @endif
                @endauth

                function getRides(rideId){ 
                    map.retrieveRideMarkers(rideId, true, true)(); 
                }

            } catch (e) {
                console.error('Initialization error:', e);
            }
        });
    </script>
@endpush