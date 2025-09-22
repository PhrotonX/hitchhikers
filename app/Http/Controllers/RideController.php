<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRideRequest;
use App\Http\Requests\UpdateRideRequest;
use App\Models\Ride;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RideController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.ride.show');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create');

        return view('pages.rides.create');
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @return array status and ride object.
     */    
    public function store(StoreRideRequest $request)
    {
        $result = $this->onStore($request);

        return view('pages.rides.view', $result);
    }

    protected function onStore(StoreRideRequest $request){
        $this->authorize('create');

        $ride = new Ride();
        $ride = $request->validated();
        $ride->rating = 0;
        $ride->status = "";

        $ride->save();

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ride $ride)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRideRequest $request, Ride $ride)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ride $ride)
    {
        //
    }
}
