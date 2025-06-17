<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\SpecieFamily;
use App\Models\User;

class SpecieFamilyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array('FAMILY_INDEX', $user->getUserPermissions());
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SpecieFamily $specieFamily): bool
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
    public function update(User $user, SpecieFamily $specieFamily): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SpecieFamily $specieFamily): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SpecieFamily $specieFamily): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SpecieFamily $specieFamily): bool
    {
        return false;
    }
}
