<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReplyRequest;
use App\Http\Requests\UpdateReplyRequest;
use App\Models\Reply;
use App\Models\Review;

class ReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            "replies" => Reply::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
        
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReplyRequest $request, int $review)
    {
        $validated = $request->validate();
        
        $reply = new Reply();
        $reply->fill($validated);
        $reply->replied_review_id = $review;
        $reply->user_id = Auth::user()->id;
        $reply->ride_id = $ride->id;
        $reply->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(int $reply)
    {
        $result = Reply::where('id', $reply);
        return response()->json(
            $result
        );
    }

    public function getReplies(int $review){
        $result = Reply::where('replied_review_id', $review);
        return response()->json(
            $result
        );
    }

    /**
    * Show the form for editing the specified resource.
    */
    // public function edit(Reply $reply)
    // {
    //      //
    // }

    /**
    * Update the specified resource in storage.
    */
    public function update(UpdateReplyRequest $request, int $reply)
    {
        $result = Reply::where('id', $reply)[0];
        
        Log::debug("ReplyController.update(): ");
        Log::debug($result->id);

        $result->fill($request->validated());
        $result->update();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $reply)
    {
        $result = Reply::where('id', $reply)[0];
        
        $result->delete();
    }
}
