<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleDriver;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

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
        return $this->checkIfDriverOwnsVehicle($user, $vehicle);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vehicle $vehicle): bool
    {
        return $this->checkIfDriverOwnsVehicle($user, $vehicle);
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

    protected function checkIfDriverOwnsVehicle(User $user, Vehicle $vehicle){
        // Check if the user owns the vehicle.
        // VehicleDriver IDs are unique.

        // Get the associative VehicleDriver object from the Vehicle object to obtain access to
        // driver ID.
        $vehicleDriver = VehicleDriver::where('vehicle_id', $vehicle->id)->first()->get();

        // Compare the driver ID of the vehicle to the current user's driver ID.
        return $user->getDriverAccount()?->id ?? 0 == $vehicleDriver->driver->id; 
    }
}
