<?php

namespace App\Policies;

use App\Models\PersonalInfo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PersonalInfoPolicy
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
    public function view(User $user, PersonalInfo $personalInfo): bool
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
    public function update(User $user, PersonalInfo $personalInfo): bool
    {
        if($user->id == $personalInfo->user_id) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PersonalInfo $personalInfo): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PersonalInfo $personalInfo): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PersonalInfo $personalInfo): bool
    {
        return false;
    }
}
