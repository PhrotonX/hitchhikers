<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use App\Models\VehicleDriver;
use App\Models\Ride;
use App\Http\Requests\UpdateVehicleLocationRequest;
use App\Http\Requests\UpdateVehicleStatus;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Retrieve URL parameters.
        $north = $request->input('lat-north');
        $south = $request->input('lat-south');
        $east = $request->input('lng-east');
        $west = $request->input('lng-west');

        // Get rides based on a range of coordinates that forms a bounding box.
        $results = Vehicle::query('latitude', 'BETWEEN', $north, 'AND', $south, 'AND',
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
        return view('pages.vehicle.create');
    }

    /**
     * Store a newly created resource in storage
     * 
     * @return RedirectResponse Page for creating vehicles.
     */
    public function store(StoreVehicleRequest $request): RedirectResponse
    {
        $results = $this->onStore($request);

        return redirect()->route('vehicle.show', $results);
    }

    /**
     * Store a newly created resource in storage and returns a 
     * @return JsonResponse Consists of ```redirect``` for creating vehicles.
     */
    public function storeAPI(StoreVehicleRequest $request): JsonResponse{
        $this->onStore($request);

        return response()->json([
            'redirect' => 'vehicle.create',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    protected function onStore(StoreVehicleRequest $request): array{
        // $this->authorize('create');

        $vehicle = new Vehicle();

        $vehicle->fill($request->validated());

        $vehicle->save();

        // On save to associative tables
        $vehicleDriver = new VehicleDriver;
        $vehicleDriver->vehicle_id = $vehicle->id;
        $vehicleDriver->driver_id = Auth::user()->getDriverAccount()->id;

        $vehicleDriver->save();

        return [
            'vehicle' => $vehicle,
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        return view('pages.vehicle.view', [
            'vehicle' => $vehicle,
        ]);
    }

    public function get(Vehicle $vehicle){
        return response()->json([
            'vehicle' => $vehicle,
        ]);
    }

    public function getRides(Vehicle $vehicle){
        $results = Ride::where('vehicle_id', $vehicle->id)->get();
        return response()->json([
            'rides' => $results,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        return view('pages.vehicle.edit', [
            'vehicle' => $vehicle,
        ]);
    }

    /**
     * Update the specified resource in storage and returns a page.
     * 
     * @param Vehicle The vehicle object
     * @return RedirectReponse ```vehicle```
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): RedirectResponse
    {
        $this->onUpdate($request, $vehicle);

        return redirect()->route('vehicle.show', [
            'vehicle' => $vehicle,
        ]);
    }

    /**
     * Update the specified resource in storage and returns a page.
     * 
     * @param Vehicle The vehicle object
     * @return JsonResponse ```redirect``` and ```vehicle```
     */
    public function updateAPI(UpdateVehicleRequest $request, Vehicle $vehicle): JsonResponse
    {
        $this->onUpdate($request, $vehicle);

        return response()->json([
            'redirect' => 'vehicle.view',
            'vehicle' => $vehicle,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    protected function onUpdate(UpdateVehicleRequest $request, Vehicle $vehicle){
        // $this->authorize('update', $vehicle);

        $vehicle->fill($request->validated());

        $vehicle->save();
    }

    public function updateLocation(UpdateVehicleLocationRequest $request, Vehicle $vehicle){
        $this->authorize("update", $vehicle);

        $vehicle->update($request->all());

        return response()->json([
            "vehicle" => $vehicle
        ], 200);
    }

    public function updateStatus(UpdateVehicleStatus $request, Vehicle $vehicle)
    {
        $this->authorize("update", $vehicle);

        $vehicle->update($request->all());

        return response()->json([
            "vehicle" => $vehicle
        ], 200);
    }

    /**
     * Remove the specified resource from storage with a RedirectResponse.
     * 
     * @param vehicle The resource to be deleted.
     * @return RedirectResponse Returns back page.
     */
    public function destroy(Vehicle $vehicle)
    {
        $results = $this->onDestroy($vehicle);

        return back()->with($results);
    }

    protected function onDestroy(Vehicle $vehicle): array{
        $this->authorize('delete', $vehicle);

        // Delete the data from associative tables.
        VehicleDriver::where('vehicle_id', $vehicle->id)
                    //  ->where('driver_id', Auth::user()->getDriverAccount()?->id ?? 0)
                     ->delete();

        // @TODO: Delete the foriegn keys from other tables such as the rides table.

        // Delete the actual vehicle data.

        $vehicle->delete();

        return [
            'status' => "Vehicle $vehicle->vehicle_name deleted",
        ];
    }
}
