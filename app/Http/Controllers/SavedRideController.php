<?php

namespace App\Http\Controllers;

use App\Models\SavedRide;
use App\Models\Ride;
use App\Http\Requests\StoreSavedRideRequest;
use App\Http\Requests\UpdateSavedRideRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SavedRideController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display all of listing of the resource.
     */
    public function all()
    {
        $this->authorize('viewAny', SavedRide::class);

        $results = SavedRide::all();
        return response()->json([
            "saved_rides" => $results,
            "rides" => $this->getAssociatedRides($results),
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewMany', SavedRide::class);

        $results = SavedRide::where('user_id', Auth::user()->id);
        return response()->json([
            "saved_rides" => $results,
            "rides" => $this->getAssociatedRides($results),
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

    public function get(int $savedRideFolder){
        // If ungrouped...
        if($savedRideFolder == 0){
            if(!is_numeric($savedRideFolder)){
                return response("Saved Ride Folder ID must be numeric", 400);
            }

            $data = SavedRide::selectRawWhere('user_id = ' . Auth::user()->id);

            if($data == null){
                return response('Not Found', 404);
            }

            Log::debug($data);

            $this->authorize('view', $data);

            return response()->json([
                "saved_rides" => $data,
                "rides" => $this->getAssociatedRides($data),
            ]);
        }
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

        return response()->json([
            "saved_ride" => $data,
            "ride" => $this->getAssociatedRide($data),
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
    public function destroy(int $savedRide)
    {
        $data = SavedRide::find($savedRide);

        if($data == null){
            return response('Not Found', 404);
        }

        $this->authorize('forceDelete', $data);

        $data->delete();
    }

    private function getAssociatedRide($savedRide){
        return Ride::find($savedRide->ride_id);
    }

    private function getAssociatedRides($savedRides){
        $results = [];
        $count = 0;
        foreach($savedRides as $savedRide){
            $results[$count] = $this->getAssociatedRide($savedRide);
            $count++;
        }

        return $results;
    }
}
