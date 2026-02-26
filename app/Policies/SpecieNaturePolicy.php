<?php

namespace App\Policies;

use App\Models\SpecieNature;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SpecieNaturePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array('NATURE_OF_SPECIES_INDEX', $user->getUserPermissions());
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SpecieNature $specieNature): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
         return in_array('NATURE_OF_SPECIES_CREATE', $user->getUserPermissions());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SpecieNature $specieNature): bool
    {
        return in_array('NATURE_OF_SPECIES_UPDATE', $user->getUserPermissions());
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SpecieNature $specieNature): bool
    {
       return in_array('NATURE_OF_SPECIES_DELETE', $user->getUserPermissions());
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SpecieNature $specieNature): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SpecieNature $specieNature): bool
    {
        return in_array('NATURE_OF_SPECIES_DELETE', $user->getUserPermissions());   
    }
}
