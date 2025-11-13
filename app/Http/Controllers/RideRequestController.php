<?php

namespace App\Http\Controllers;

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRideRequestRequest $request)
    {
        
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
    public function update(Request $request, RideRequest $rideRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RideRequest $rideRequest)
    {
        //
    }
}
