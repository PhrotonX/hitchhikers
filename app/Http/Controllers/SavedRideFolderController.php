<?php

namespace App\Http\Controllers;

use App\Models\SavedRideFolder;
use App\Http\Requests\StoreSavedRideFolderRequest;
use App\Http\Requests\UpdateSavedRideFolderRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SavedRideFolderController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', SavedRideFolder::class);

        $results = SavedRideFolder::all();

        return response()->json([
            $results,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSavedRideFolderRequest $request)
    {
        $this->authorize('create', SavedRideFolder::class);

        $data = new SavedRideFolder();
        $data->fill($request->validated());
        $data->save();

        return response()->json([
            $data,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $savedRideFolder)
    {
        $data = SavedRideFolder::find($savedRideFolder);

        if($data == null){
            return response("Not found", 404);
        }

        $this->authorize('view', $data);

        return response()->json([
            $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSavedRideFolderRequest $request, int $savedRideFolder)
    {
        $data = SavedRideFolder::find($savedRideFolder);

        if($data == null){
            return response("Not found", 404);
        }

        $this->authorize('update', $data);

        $data->fill($request->validated());

        return response()->json([
            $data,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $savedRideFolder)
    {
        $data = SavedRideFolder::find($savedRideFolder);

        if($data == null){
            return response("Not found", 404);
        }

        $this->authorize('forceDelete', $data);

        $data->delete();
    }
}
