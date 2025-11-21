<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\RideRequest;
use App\Models\Vehicle;
use App\Http\Requests\StoreRideRequestRequest;
use App\Http\Requests\UpdateRideRequestRequest;
use App\Http\Requests\UpdateRideRequestStatusRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RideRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($ride)
    {
        return response()->json([
            RideRequest::where('ride_id', $ride),
        ]);
    }

    public function create($rideId){
        $ride = Ride::find($rideId);

        return view('pages.ride_request.create', [
            'ride' => $ride,
        ]);
    }

    public function created(){
        $id = Auth::user()->id;

        $results = RideRequest::where('sender_user_id', $id);
        $rides = [];
        $vehicles = [];

        foreach ($results as $key => $value) {

            $ride = Ride::find($value->ride_id);
            $rides[$value->ride_id] = $ride;
        }

        foreach ($rides as $key => $value) {

            $vehicle = Vehicle::find($value->vehicle_id);
            $vehicles[$value->vehicle_id] = $vehicle;
        }

        return view('pages.ride_request.created', [
            'rideRequests' => $results,
            'rides' => $rides,
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRideRequestRequest $request)
    {
        $rideRequest = new RideRequest();
        $rideRequest->fill($request->validated());

        $date = new \DateTimeImmutable();

        // Fill up missing fields.
        $rideRequest->to_latitude = round($rideRequest->to_latitude, 7);
        $rideRequest->to_longitude = round($rideRequest->to_longitude, 7);
        $rideRequest->from_latitude = round($rideRequest->from_latitude, 7);
        $rideRequest->from_longitude = round($rideRequest->from_longitude, 7);
        $rideRequest->status = "pending";
        $rideRequest->status_updated_at = $date->format("Y-m-d H:i:s");
        $rideRequest->sender_user_id = Auth::user()->id;

        $rideRequest->save();

        return view('pages.index', [
            'status' => 'Ride request saved successfully!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $rideRequest)
    {
        $data = RideRequest::find($rideRequest);

        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRideRequestRequest $request, int $rideRequest)
    {
        $data = RideRequest::find($rideRequest);

        $data->fill($request->validated());
        $data->update();
    }

    /**
     * Update the status field of the specified resource in storage.
     */
    public function updateStatus(UpdateRideRequestStatusRequest $request, int $rideRequest)
    {
        $data = RideRequest::find($rideRequest);

        $data->fill($request->validated());
        $data->status_updated_at = $data->now();
        $data->update();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $rideRequest)
    {
        $data = RideRequest::find($rideRequest);
        
        $data->delete();
    }
}
