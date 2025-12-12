<?php

namespace App\Http\Controllers;

// use App\Http\Requests\StoreDestinationRequest;
// use App\Http\Requests\UpdateDestinationRequest;
use App\Models\Ride;
use App\Models\RideDestination;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class RideDestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * Expects parameters lat-north, lat-south, lng-east, and lng-west.
     */
    public function index(Request $request)
    {
        // Retrieve URL parameters.
        $north = $request->input('lat-north');
        $south = $request->input('lat-south');
        $east = $request->input('lng-east');
        $west = $request->input('lng-west');

        // Get rides based on a range of coordinates that forms a bounding box.
        $results = RideDestination::query('latitude', 'BETWEEN', $north, 'AND', $south, 'AND',
            'longitude', 'BETWEEN', $east, 'AND', $west)->get();

        return response()->json([
            "results" => $results,
        ]);
    }

    public function get(Ride $ride)
    {
        $results = RideDestination::where('ride_id', $ride->id)->get();

        return response()->json([
            "results" => $results,
        ]);
    }

    public function search(Request $request){
        $ride_address = $request->input('ride_address');
        // $ride_name = $request->input('ride_name');

        $results = RideDestination::where('ride_address', 'LIKE', "%$ride_address%")->get();

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

        return response()->json([
            'ride_destinations' => $results,
            'rides' => $rides,
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(StoreDestinationRequest $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     */
    public function show(Destination $destination)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Destination $destination)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateDestinationRequest $request, Destination $destination)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Destination $destination)
    // {
    //     //
    // }
}
