<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleDriver;
use Illuminate\Auth\Access\Response;
use Illuminate\Facades\Support\Log;

class VehiclePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Vehicle $vehicle): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isDriver();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vehicle $vehicle): bool
    {
        //VehicleDriver IDs are unique.
        $vehicleDriver = VehicleDriver::where('vehicle_id', $vehicle->id)->first()->get();
        return $user->getDriverAccount()?->id ?? 0 == $vehicleDriver->driver->id; 
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vehicle $vehicle): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Vehicle $vehicle): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Vehicle $vehicle): bool
    {
        return false;
    }
}
