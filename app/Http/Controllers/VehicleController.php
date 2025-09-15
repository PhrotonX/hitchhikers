<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class VehicleController extends Controller
{
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
        $vehicle = new Vehicle();

        $vehicle->fill($request->validated());

        $vehicle->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        //
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

        return redirect()->route('vehicle.view', [
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
