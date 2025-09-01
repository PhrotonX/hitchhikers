<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserAddressesRequest;
use App\Http\Requests\UpdateUserAddressesRequest;
use App\Models\UserAddresses;

class UserAddressesController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserAddressesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserAddresses $userAddresses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserAddresses $userAddresses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserAddressesRequest $request, UserAddresses $userAddresses)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserAddresses $userAddresses)
    {
        //
    }
}
