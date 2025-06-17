<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Specie;
use App\Models\User;

class SpeciePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array('SPECIES_INDEX', $user->getUserPermissions());
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Specie $specie): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Specie $specie): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Specie $specie): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Specie $specie): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Specie $specie): bool
    {
        return false;
    }
}
