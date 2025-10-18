<?php

namespace App\Http\Controllers;

// use App\Http\Requests\StoreDestinationRequest;
// use App\Http\Requests\UpdateDestinationRequest;
use App\Models\RideDestination;
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
