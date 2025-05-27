<?php

namespace App\Policies;

use App\Models\LtpApplication;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LtpApplicationPolicy
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
    public function view(User $user, LtpApplication $ltpApplication): bool
    {
        if($user->id == $ltpApplication->permittee->user_id || in_array('LTP_APPLICATION_INDEX', $user->getUserPermissions()) ) {
            return true;
        }
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
    public function update(User $user, LtpApplication $ltpApplication): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LtpApplication $ltpApplication): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LtpApplication $ltpApplication): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LtpApplication $ltpApplication): bool
    {
        return false;
    }

    public function uploadReceipt(User $user, LtpApplication $ltpApplication) {
        return $user->id == $ltpApplication->permittee->user_id;
    }
}
