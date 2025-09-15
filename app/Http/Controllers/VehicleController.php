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
        return redirect()->route('pages.vehicle.create');
    }

    /**
     * Store a newly created resource in storage
     * 
     * @return RedirectResponse Page for creating vehicles.
     */
    public function store(StoreVehicleRequest $request): RedirectResponse
    {
        $this->onStore($request);

        return redirect()->route('pages.vehicle.create');
    }

    /**
     * Store a newly created resource in storage and returns a 
     * @return JsonResponse Consists of ```redirect``` for creating vehicles.
     */
    public function storeAPI(StoreVehicleRequest $request): JsonResponse{
        $this->onStore($request);

        return response()->json([
            'redirect' => 'pages.vehicle.create',
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
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        //
    }
}
