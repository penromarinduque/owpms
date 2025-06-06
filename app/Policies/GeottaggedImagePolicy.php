<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\GeottaggedImage;
use App\Models\User;

class GeottaggedImagePolicy
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
    public function view(User $user, GeottaggedImage $geottageImage): bool
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
    public function update(User $user, GeottaggedImage $geottageImage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GeottaggedImage $geottageImage): bool
    {
        $permissions = $user->getUserPermissions();
        $ltp_application = $geottageImage->ltpApplication;
        if($user->id == $ltp_application->permittee->user_id && in_array($ltp_application->application_status, ["paid", "inspection-rejected"]) && $geottageImage->uploaded_by == "permittee"){
            return true;
        }
        if(in_array('LTP_APPLICATION_INSPECT', $permissions) && $ltp_application->application_status == "paid" && $geottageImage->uploaded_by == "internal"){
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GeottaggedImage $geottageImage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GeottaggedImage $geottageImage): bool
    {
        return false;
    }
}
