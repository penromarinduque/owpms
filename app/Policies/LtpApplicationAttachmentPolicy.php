<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\LtpApplicationAttachment;
use App\Models\User;

class LtpApplicationAttachmentPolicy
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
    public function view(User $user, LtpApplicationAttachment $ltpApplicationAttachment): bool
    {
        if($user->usertype == 'admin' || $user->usertype == 'internal' || $ltpApplicationAttachment->ltpApplication->permittee->user_id == $user->id){ 
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
    public function update(User $user, LtpApplicationAttachment $ltpApplicationAttachment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LtpApplicationAttachment $ltpApplicationAttachment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LtpApplicationAttachment $ltpApplicationAttachment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LtpApplicationAttachment $ltpApplicationAttachment): bool
    {
        return false;
    }
}
