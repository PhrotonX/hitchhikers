<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfitLogsRequest;
use App\Http\Requests\UpdateProfitLogsRequest;
use App\Models\ProfitLogs;

class ProfitLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfitLogsRequest $request)
    {
        $profitLog = new ProfitLogs();

        $profitLog->fill($request->validated());
        $profitLog->save();

        return response()->json([
            $profitLog,
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
    public function destroy(ProfitLogs $profitLogs)
    {
        //
    }
}
