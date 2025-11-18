<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\RideRequest;
use App\Http\Requests\StoreRideRequestRequest;
use Illuminate\Http\Request;

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRideRequestRequest $request)
    {
        $rideRequest = new RideRequest();
        $rideRequest->fill($request->validated());
        $rideRequest->save();

        return response()->json([
            $rideRequest,
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
        $data->save();
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
