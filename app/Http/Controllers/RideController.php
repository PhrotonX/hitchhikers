<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRideRequest;
use App\Http\Requests\UpdateRideRequest;
use App\Http\Requests\UpdateRideStatus;
use App\Models\User;
use App\Models\Review;
use App\Models\Ride;
use App\Models\RideDestination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RideController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of drivers rides.
     */
    public function index(int $driver)
    {
        $rides = Ride::where('driver_id', $driver)->get();

        return response()->json([
            $rides,
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Ride::class);

        return view('pages.ride.create', [
            'driverVehicles' => Auth::user()->getVehicleDriver(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @return array status and ride object.
     */    
    public function store(StoreRideRequest $request)
    {
        Log::debug("RideController.store(): Request called");
        $result = $this->onStore($request);

        return redirect()->route('ride.show', $result['ride']->id)
            ->with('success', $result['status']);
    }

    protected function onStore(StoreRideRequest $request){
        Log::debug("RideController.oncreate(): Authorizing...");

        //@TODO: Must verify that the user is a driver and owns the selected vehicle.
        $this->authorize('create', Ride::class);

        Log::debug("RideController.oncreate(): Authorized...");

        $ride = new Ride();

        Log::debug("RideController.oncreate(): Validating...");

        $validated = $request->validated();
        $ride->driver_id = Auth::user()->getDriverAccount()->id;
        $ride->ride_name = $validated['ride_name'];
        $ride->minimum_fare = $validated['minimum_fare'];
        $ride->fare_rate = $validated['fare_rate'];
        $ride->vehicle_id = $validated['vehicle_id'];
        $ride->rating = 0;
        $ride->status = "";

        Log::debug("RideController.oncreate(): Saving...");

        $ride->save();

        Log::debug("RideController.oncreate(): Saved...");

        for($i = 0; $i < count($validated['order']); $i++){
            $destinations = new RideDestination();
            $destinations->ride_id = $ride->id;
            $destinations->latitude = $validated['latitude'][$i];
            $destinations->longitude = $validated['longitude'][$i];
            $destinations->order = $validated['order'][$i];
            $destinations->ride_address = $validated['ride_address'][$i];
            $destinations->save();
        }

        return [
            'ride' => $ride,
            'status' => "Ride $ride->ride_name saved!",
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Ride $ride)
    {
        return view('pages.ride.view', [
            'ride' => $ride,
            'destinations' => $ride->getRideDestinations()->orderBy('order')->get(),
        ]);
    }

    public function get(Ride $ride){
        return response()->json([
            "ride" => $ride,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ride $ride)
    {
        $this->authorize('update', $ride);

        return view('pages.ride.edit', [
            'ride' => $ride,
            'destinations' => $ride->getRideDestinations()->orderBy('order')->get(),
            'driverVehicles' => Auth::user()->getVehicleDriver(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRideRequest $request, Ride $ride)
    {
        Log::debug("RideController.update(): Request called");
        
        Log::debug("RideController.update(): Authorizing...");

        //@TODO: Must verify that the user is a driver and owns the selected vehicle.
        $this->authorize('update', $ride);

        Log::debug("RideController.update(): Authorized...");

        Log::debug("RideController.update(): Validating...");

        $validated = $request->validated();
        $ride->ride_name = $validated['ride_name'];
        $ride->minimum_fare = $validated['minimum_fare'];
        $ride->fare_rate = $validated['fare_rate'];
        $ride->vehicle_id = $validated['vehicle_id'];

        Log::debug("RideController.update(): Saving...");

        $ride->save();

        Log::debug("RideController.update(): Saved...");

        // Delete existing destinations
        RideDestination::where('ride_id', $ride->id)->delete();

        // Add new destinations
        for($i = 0; $i < count($validated['order']); $i++){
            $destinations = new RideDestination();
            $destinations->ride_id = $ride->id;
            $destinations->latitude = $validated['latitude'][$i];
            $destinations->longitude = $validated['longitude'][$i];
            $destinations->order = $validated['order'][$i];
            $destinations->ride_address = $validated['ride_address'][$i];
            $destinations->save();
        }

        return redirect()->route('ride.show', $ride->id)
            ->with('success', "Ride $ride->ride_name updated!");
    }

    public function updateStatus(UpdateRideStatus $request, Ride $ride)
    {
        $this->authorize("update", $ride);

        $ride->update($request->all());

        return response()->json([
            "ride" => $ride
        ], 200);
    }

    /**
     * Show the delete confirmation page.
     */
    public function delete(Ride $ride)
    {
        $this->authorize('delete', $ride);

        return view('pages.ride.delete', [
            'ride' => $ride,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ride $ride)
    {
        $this->authorize('delete', $ride);

        $rideName = $ride->ride_name;
        
        // Delete associated destinations
        RideDestination::where('ride_id', $ride->id)->delete();
        
        // Delete the ride
        $ride->delete();

        return redirect()->route('home')
            ->with('success', "Ride '$rideName' has been deleted successfully.");
    }
}
