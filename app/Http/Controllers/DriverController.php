<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use App\Models\Driver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    public function store(StoreDriverRequest $request): RedirectResponse
    {
        Log::debug('DriverController::onStore()');
        $this->onStore($request);

        Log::debug('Data saved');
        return redirect()->route('home', [
            'status' => __('string.driving_program_enrollment_success'),
        ]);
    }

    public function storeAPI(StoreDriverRequest $request): JsonResponse
    {
        $this->onStore($request);

        return response()->json([
            'redirect' => 'home',
            'status' => __('string.driving_program_enrollment_success'),
        ]);
    }

    protected function onStore(StoreDriverRequest $request){
        Log::debug('DriverController::onStore()');
        $validated = $request->validated();

        Log::debug('DriverController::onStore()');

        $driver = new Driver();
        $driver->fill($validated);
        $driver->user_id = Auth::user()->id;
        
        $driver->save();
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
