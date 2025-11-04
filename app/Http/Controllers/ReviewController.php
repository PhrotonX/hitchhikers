<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Review;
use App\Models\Ride;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            "reviews" => Review::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request, Ride $ride)
    {
        $review = new Review();
        $review->fill($request->validated());
        $review->user_id = Auth::user()->id;
        $review->ride_id = $ride->id;
        $review->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(int $review)
    {
        $result = Review::where('id', $review);
        return response()->json(
            $result
        );

        // return response()->json([
        //     $review,
        // ]);
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(Review $review)
    // {
        
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    public function update(UpdateReviewRequest $request, int $review)
    {
        $result = Review::where('id', $review)[0];
        
        Log::debug("ReviewController.update(): ");
        Log::debug($result->id);

        $result->fill($request->validated());
        $result->update();
    }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    public function destroy(int $review)
    {
        $result = Review::where('id', $review)[0];
        
        $result->delete();
    }
}
