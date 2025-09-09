<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use App\Models\Driver;

class DriverController extends Controller
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
        return response()->json([
            'redirect' => 'pages.driver.enroll_account',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDriverRequest $request): JsonResponse
    {
        $validated = $request;

        $driver = Driver::create([
            'user_id' => Auth::user()->id,
            'driver_account_name' => $validated->driver_account_name,
            'driver_type' => $validated->driver_type,
            'account_status' => $validated->account_status,
            'company' => $validated->company,
        ]);

        $driver->save();

        return response()->json([
            'redirect' => 'home',
            'status' => __('string.driving_program_enrollment_success'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Driver $driver)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDriverRequest $request, Driver $driver)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        //
    }
}
