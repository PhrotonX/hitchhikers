<?php

namespace App\Http\Controllers;

use App\Models\SavedRide;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SavedRideController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', SavedRide::class);

        $results = SavedRide::all();
        return response()->json([
            $results, 
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $savedRide)
    {
        $data = SavedRide::find($savedRide);

        $this->authorize('view', $data);

        $results = SavedRide::all();
        return response()->json([
            $results, 
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SavedRide $savedRide)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SavedRide $savedRide)
    {
        //
    }
}
