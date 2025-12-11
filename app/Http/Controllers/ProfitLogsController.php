<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfitLogsRequest;
use App\Http\Requests\UpdateProfitLogsRequest;
use App\Models\ProfitLogs;
use App\Models\Ride;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class ProfitLogsController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny');
        return view('pages.profit.index', [
            ProfitLogs::where('driver_id', Auth::user()->getDriverAccount()->id),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfitLogsRequest $request)
    {
        try {
            $this->authorize('create', ProfitLogs::class);
            
            $profitLog = new ProfitLogs();

            $profitLog->fill($request->validated());
            $profitLog->driver_id = Auth::user()->getDriverAccount()->id;
            $profitLog->save();

            return response()->json([
                'success' => true,
                'data' => $profitLog,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function showFromRide(Ride $ride){
        $this->authorize('viewAny');

        return view('pages.profit.ride', [
            ProfitLogs::where('driver_id', Auth::user()->getDriverAccount()->id),
            $ride
        ]);
    }

    /**
     * Display the specified resource.
     */
    // public function show(ProfitLogs $profitLogs)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(ProfitLogs $profitLogs)
    // {
    //     //
    // }
}
