<?php

namespace App\Policies;

use App\Models\Ride;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RidePolicy
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
    public function view(User $user, Ride $ride): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create rides.
     * 
     * A user can only create a ride if the user is a driver and has at least one vehicle.
     */
    public function create(User $user): bool
    {
        return $user->isDriver();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ride $ride): bool
    {
        return $user->isDriver() && $ride->driver_id == $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ride $ride): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ride $ride): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ride $ride): bool
    {
        return false;
    }
}
