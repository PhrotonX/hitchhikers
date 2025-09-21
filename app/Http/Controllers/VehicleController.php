<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use App\Models\VehicleDriver;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $this->onStore($request);

        return redirect()->route('vehicle.create');
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
    protected function onStore(StoreVehicleRequest $request){
        // $this->authorize('create');

        $vehicle = new Vehicle();

        $vehicle->fill($request->validated());

        $vehicle->save();

        // On save to associative tables
        $vehicleDriver = new VehicleDriver;
        $vehicleDriver->vehicle_id = $vehicle->id;
        $vehicleDriver->driver_id = Auth::user()->getDriverAccount()->id;

        $vehicleDriver->save();
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        //
    }
}
