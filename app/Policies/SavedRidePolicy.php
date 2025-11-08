<?php

namespace App\Policies;

use App\Models\SavedRide;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SavedRidePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if($user->isPrivileged('owner')){
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SavedRide $savedRide): bool
    {
        return $user->id == $savedRide->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SavedRide $savedRide): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SavedRide $savedRide): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SavedRide $savedRide): bool
    {
        
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SavedRide $savedRide): bool
    {
        return false;
    }
}
