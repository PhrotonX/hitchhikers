@extends('layouts.app')
<x-map-head/>

@push('head')
    @vite(['resources/css/index.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <style>
        :root {
            /* Light Mode Colors (Original) */
            --primary: #1E3A8A;
            --primary-light: #3B5FCF;
            --secondary: #10B981;
            --accent: #F59E0B;
            --error: #EF4444;
            --background: #F9FAFB;
            --background-dark: #0F172A;
            --background-hover: #f3f3f3;
            --text-dark: #1F2937;
            --text-light: #6B7280;
            --border: #E5E7EB;
            --card-bg: #FFFFFF;
            --shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 4px 12px rgba(0, 0, 0, 0.12);

            /* Night Mode Colors */
            --night-primary: #4C8DFF;
            --night-secondary: #4BD36A;
            --night-accent: #FFD85A;
            --night-bg: #121212;
            --night-card-bg: #1A1A1A;
            --night-border: #2A2A2A;
            --night-text-primary: #EDEDED;
            --night-text-secondary: #BBBBBB;
        }

        *{
            margin: 0;
            padding: 0;
            box-sizing : border-box;
        }

        body{
            display: flex;
            flex-direction: column;
            height: 100%;
            font-family: "Poppins";
            color: var(--text-dark);
            background-color: var(--background);
            line-height: 1.6;
        }

        .container{
            width: 100%;
            max-width: 2560px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /*----------- TOOLBAR -----------*/
        .toolbar{
            background-color: var(--card-bg);
            box-shadow: var(--shadow);
            position: sticky;
            justify-content: center;
            top: 0;
            z-index: 100;
            padding: 15px 0;    
        }
        .toolbar-content{
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        /*----------- END TOOLBAR -----------*/

        /*----------- LOGO -----------*/

        .logo{
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .logo-img{
            width: 70px;
            height: auto;
        }

        .logo-text{
            font-weight: 700;
            font-size: 32px;
            color: var(--primary);
        }

        /*----------- END LOGO -----------*/

        /*----------- NAVIGATION LINKS -----------*/

        .nav-links{
            display: flex;
            gap: 30px;
        }

        .nav-links a{
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .nav-links a:hover{
            color: var(--text-light);
        }

        /*----------- END NAVIGATION LINKS -----------*/


        /*----------- SEARCH BAR -----------*/

        .search-bar{
            display: flex;
            background-color: white;
            border-radius: 50px;
            padding: 8px 16px;
            box-shadow: var(--shadow);
            margin: 0 20px;
            flex-grow: 1;
            max-width: 500px;
        }

        .search-input{
            border:none;
            outline: none;
            padding: 8px;
            flex-grow: 1;
            font-size: 16px;
        }

        .search-button{
            background-color: var(--accent);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 8px 16px;
            font-weight: 600;
            cursor: pointer;
        }

        .search-button:hover{
            background-color: #E58A09;
        }
        /*----------- END SEARCH BAR -----------*/

        .user-actions{
            display: flex;
            align-items: center;
            justify-self: space-between;
            gap: 85px;
        }

        /*----------- USER ICON -----------*/


        .user-icon{
            display: flex;
        }

        .user-icon i{
            width: 50px;
            height: 50px;
            border-image-repeat: 0%;
            background-color: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .user-icon i:hover{
            background-color: #1d316a;
        }

        /*----------- END USER ICON -----------*/

        /*----------- NOTIFICATION -----------*/

        .notif-icon{
            font-size: 25px;
            position: relative;
            cursor: pointer;
            display: flex;
            align-items: center;
            color: var(--background-dark);
        }

        .notif-icon:hover{
            color: var(--primary-light);
        }

        .notif-badge{
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--error);
            color: white;
            font-size: 10px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notif-label{
            color: var(--text-light);
            font-size: large;
            display: flex;
            justify-content: center;
            padding: 10px;
        }

        .notif-list{
            position: absolute;
            top: 100%;
            right: 5%;
            width: 250px;
            height: auto;
            background: white;
            box-shadow: 0px 0px 15px rgba(0, 0, 0.12, 0);
            border: 0.5px solid var(--border);
            border-radius: 0 0 8px 8px;
            display: none;
            flex-direction: column;
            overflow: hidden;
            z-index:200;
            justify-content: space-between;
            flex-shrink: 0;
            border-top: 1px solid var(--border);
        }

        .notif-list.active{
            display: block;
        }

        .notif-list ul{
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .notif-list ul li a{
            display: block;
            padding: 10px;
            color: var(--text-dark);
            text-decoration: none;
            transition: background 0.2s;
            border-top: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .view-all{
            text-align: center;
            padding: 10px;
            background: var(--primary-light);
            border-top: 1px solid var(--border);
            cursor: pointer;
        }
        .viewAll-link{
            display: block;
            text-decoration: none;   
            color: white;
        }

        .notif-list ul li a:hover{
            background: var(--background-hover);
        }
        .view-all:hover{
            background: var(--secondary);
        }

        .view-all:hover .viewAll-link{
            color: var(--text-dark);
        }
        /*----------- END NOTIFICATION -----------*/

        /*----------- END THEME -----------*/

        .theme-toggle{
            font-size: 25px;
            position: relative;
            cursor: pointer;
            display: flex;
            align-items: center;
            border: none;
            border-radius: 0%;
            background-color: white;
        }

        .theme-toggle:hover{
            color: var(--primary-light);
        }

        body.dark-mode{
            background-color: var(--background-dark);
            color: white;
        }

        body.dark-mode .toolbar,
        body.dark-mode .mlay-side,
        body.dark-mode .mid-section,
        body.dark-mode .bottom-section{
            background-color: #1a243d;
            color: white;
        }

        body.dark-mode .nav-links a{
            color: var(--text-light);
        }

        body.dark-mode .nav-links a:hover{
            color: var(--accent);
        }

        body.dark-mode .notif-icon{
            color: white;
        }

        body.dark-mode .notif-icon:hover{
            color: var(--accent);
        }

        body.dark-mode .theme-toggle{
            border: none;
            border-radius: 0%;
            background-color: #1a243d;
            color: var(--primary-light);
        }

        body.dark-mode .theme-toggle:hover{
            color: var(--text-light);
        }

        /*----------- END THEME -----------*/

        /*-----------SIDE BAR-----------*/

        .sidebar{
            position:absolute;
            top: 100%;
            right: 0;
            width: 250px;
            height: auto;
            background: white;
            box-shadow:  0px  0px 15px rgba(0, 0, 0.12, 0);
            border: 0.5px solid var(--border);
            border-radius: 0 0 0 8px;
            display: none;
            flex-direction: column;
            overflow: hidden;
            z-index:200;
            align-items: space-between;
            flex-shrink: 0;
            border-top: 1px solid var(--border);
        }

        .sidebar-section{
            border-top: 1px solid var(--border);
            display: flex;
            flex-direction: column;
        }

        .sidebar-menu-links{
            display: block;
            padding: 12px 16px;
            text-decoration: none;
            transition: background 0.2s;
            color: var(--text-dark);
        }

        .sidebar-menu-links:hover{
            background: var(--background-hover);
            color: var(--text-dark);
        }

        .sidebar.active{
            display:flex;
        }

        /*----------- PROFILE SECTION -----------*/

        .sidebar-top{
            display: flex;
            margin: 12px;
            align-items: center;
            padding: 10px 10px;
            background:var(--card-bg);
            height: 90px;
            border: 0.1px solid var(--border);
            border-radius: 8%;
            box-shadow: 0px 0px 15px var(--shadow);
        }

        .edit-profile{
            font-size: small;
            text-decoration: none;   
            color: var(--text-light);
        }

        .profile h3{
            color: var(--text-dark);
        }

        .edit-profile:hover{
            color: var(--text-dark);
            text-decoration: underline;
        }

        /*-----------END PROFILE SECTION -----------*/

        .dashboard{
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /*-----------LOGOUT-----------*/

        .logout-icon{
            background: var(--accent);
            display: flex;
            padding: 8px 16px;
        }

        .logout-icon:hover{
            background: var(--error);
        }

        .logout-link{
            display: block;
            width: 100%;
            text-decoration: none;
            align-items: center;
            transition: background 0.2s;
            color: var(--text-dark);
        }

        .logout-icon i{
            margin-left: 12px;
            color: #0F172A;
            align-items: center;
            text-decoration: none;
            padding: 10px 14px;
            transform: scaleX(-1);
        }

        /*----------- END LOGOUT-----------*/
        /*----------- END SIDE BAR -----------*/

        /*----------- MAIN LAYOUT -----------*/

        .main-layout{
            display: flex;
        }

        .mlay-side{
            width: 280px;
            height: auto;
            background-color: var(--card-bg);
            padding: 25px 20px;
            border-right: 1px solid var(--border);
            box-shadow: var(--shadow);
        }

        .main-content{
            flex: 1;
            padding: 30px;
        }

        .top-section{
            display: flex;
            flex-direction: column;
            border-radius: 12px;
            padding: 40px;
            background:linear-gradient( to right, var(--primary-light), var(--primary) );
            color: white;
            margin-bottom: 40px;
            gap: 5px;
        }

        .mid-section{
            display: flex;
            padding: 40px;
            border-left: 8px solid var(--secondary);
            background: var(--card-bg);
            border-radius: 12px;
            margin-bottom: 40px;
            box-shadow: var(--shadow);
            color: var(--text-dark);
        }

        .bottom-section{
            display: flex;
            padding: 40px;
            border-radius: 12px;
            background: var(--background);
            margin-bottom: 40px;
            box-shadow: var(--shadow);
            color: var(--text-dark);
        }

        /*----------- END MAIN LAYOUT -----------*/

        
    </style>
@endpush

@section('content')
    <!-- MAIN LAYOUT -->
    <div class="main-layout container">
        <aside class="mlay-side">
            <!-- Left side placeholder (keeps layout consistent) -->
        </aside>

        <main class="main-content">
            <section class="top-section">
                <h4>Share Your Journey, Save Your Costs</h4>
                <p>Find rides, connect with drivers, and travel affordably.</p>
            </section>

            <!-- Map area from existing app (preserved) -->
            <section style="margin-top:16px">
                <div id="map"></div>
            </section>

            <section class="mid-section" style="margin-top:16px">
                <h3>Mid Section</h3>
            </section>

            <section class="bottom-section" style="margin-top:16px">
                <h3>Bottom Section</h3>
            </section>

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
                        <h2>Available Vehicles</h2>
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
        var passengerRequest = null;
        @auth
            @if (!Auth::user()->isDriver())
                page.loadAuthObjects({ 'saved_rides': 'saved-ride-list' });
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

        // Existing map & UI behavior preserved below (unchanged logic)
        map.setOnVehicleMarkerClick((e, data) => {
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

            var rideList = document.getElementById('ride-list');
            infobox.addEventListener('click', (e) => {
                if (e.target && e.target.id === 'ride-popup-close-btn') {
                    infobox.innerHTML = "";
                    infobox.style.display = "none";
                    map.cachedMarkers.clearLayers();
                    reviewBox.hidden = true;
                }
            });

            reviewBox.hidden = false;

            map.reverseGeocode(data.latitude, data.longitude).then((location) => {
                document.getElementById('ride-location').innerHTML = location.display_name;
            });

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

                rideList.addEventListener('change', () => {
                    let rideId = rideList.value;
                    if(rideList.value < 1){
                        viewReviewsBtn.hidden = true;
                    }else{
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

                viewReviewsBtn.addEventListener('click', () => {
                    fetch('{{env("APP_URL", "")}}' + '/ride/' + rideList.value + '/reviews')
                    .then((response) => { return response.json(); })
                    .then((data) => {
                        reviewList.hidden = false;
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
                                        reviewForm.hidden = false;
                                        reviewFormId.value = data.reviews[i].id;
                                        reviewFormDescription.value = data.reviews[i].description;
                                        reviewFormRating.value = data.reviews[i].rating;
                                        reviewFormSubmit.hidden = true;
                                        reviewFormEdit.value = data.reviews[i].rating;
                                        reviewFormEdit.hidden = false;
                                        reviewFormEdit.addEventListener('click', () => { page.onUpdateReview(data.reviews[i].id); });
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
                    }).catch((error) => { throw new Error(error); });
                });

                @auth
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
                updateSelectedRideOption();
                drivingModeOption.addEventListener('change', function(){ updateSelectedRideOption(); });
                btnDrivingMode.addEventListener('click', function(){
                    var drivingMode = "inactive";                    
                    selectedDrivingModeOption = document.getElementById("ride-option" + "-" + drivingModeOption.value);
                    if(status == "active"){ drivingMode = "inactive"; selectedDrivingModeOption.setAttribute('data-status', 'inactive'); }
                    else { drivingMode = "active"; selectedDrivingModeOption.setAttribute('data-status', 'active'); }
                    updateSelectedRideOption();

                    fetch('{{env("APP_URL", "")}}' + '/ride/'+drivingModeOption.value+'/update-status', {
                        method: "PATCH",
                        body: JSON.stringify({ status: drivingMode }),
                        headers: { "Content-type": "application/json", "Accept": "application/json", "X-CSRF-Token": document.querySelector('meta[name=csrf-token]').content }
                    }).then((response) => { return response.json(); }).then((data) => {
                        fetch('{{env("APP_URL", "")}}' + '/vehicle/'+data.ride.vehicle_id+'/update-status', {
                            method: "PATCH",
                            body: JSON.stringify({ status: drivingMode }),
                            headers: { "Content-type": "application/json", "Accept": "application/json", "X-CSRF-Token": document.querySelector('meta[name=csrf-token]').content }
                        }).then((response) => { return response.json(); }).then((vehicleData) => {
                            if(drivingMode == "active"){ map.startLiveTracking(vehicleData.vehicle.id); }
                            else{ map.stopLiveTracking(map.trackingId); }
                            map.setMarkerIcon("vehicle-" + vehicleData.vehicle.id, status + "_vehicle");
                        }).catch((error) => { throw new Error(error); });
                    }).catch((error) => { throw new Error(error); });
                });
            @endif

            function updateSelectedRideOption(){
                var selectedDrivingModeOption = document.getElementById("ride-option" + "-" + drivingModeOption.value);
                var infobox = document.getElementById("driving-mode-infobox");
                status = selectedDrivingModeOption.getAttribute('data-status');
                if(status == "active"){ btnDrivingMode.innerHTML = "Stop driving mode"; }
                else{ btnDrivingMode.innerHTML = "Start driving mode"; }
                getRides(drivingModeOption.value);
                fetch('{{env("APP_URL", "")}}' + '/ride/'+drivingModeOption.value).then((response) => { return response.json(); }).then((data) => {
                    fetch('{{env("APP_URL", "")}}' + '/api/vehicle/'+data.ride.vehicle_id).then((response) => { return response.json(); }).then((vehicleData) => {
                        infobox.innerHTML = "<p>"+vehicleData.vehicle.vehicle_name+"</p>";
                        map.getMap().setView([vehicleData.vehicle.latitude, vehicleData.vehicle.longitude], 16);
                    }).catch((error) => { throw new Error(error); });
                }).catch((error) => { throw new Error(error); });
            }
        @endauth

        function getRides(rideId){ map.retrieveRideMarkers(rideId, true, true)(); }
    });
    </script>
@endpush