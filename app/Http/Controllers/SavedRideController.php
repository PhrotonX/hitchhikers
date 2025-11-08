<?php

namespace App\Http\Controllers;

use App\Models\SavedRide;
use App\Http\Request\StoreSavedRideRequest;
use App\Http\Request\UpdateSavedRideRequest;
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
    public function store(StoreSavedRideRequest $request)
    {
        $data = new SavedRide();

        $this->authorize('create', SavedRide::class);

        $data->fill($request->validated());
        $data->save();

        return response()->json([
            $data,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $savedRide)
    {
        $data = SavedRide::find($savedRide);

        if($data == null){
            return response('Not Found', 404);
        }

        $this->authorize('view', $data);

        $results = SavedRide::all();
        return response()->json([
            $results, 
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSavedRideRequest $request, int $savedRide)
    {
        $data = SavedRide::find($savedRide);

        if($data == null){
            return response('Not Found', 404);
        }

        $this->authorize('update', $data);

        $data->fill($request->validated());
        $data->save();

        return response()->json([
            $data,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SavedRide $savedRide)
    {
        if($data == null){
            return response('Not Found', 404);
        }
    }
}
