<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\LtpPermit;
use App\Models\User;

class LtpPermitPolicy
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
    public function view(User $user, LtpPermit $ltpPermit): bool
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
    public function update(User $user, LtpPermit $ltpPermit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LtpPermit $ltpPermit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LtpPermit $ltpPermit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LtpPermit $ltpPermit): bool
    {
        return false;
    }

    public function chiefRpsSign(User $user, LtpPermit $ltpPermit): bool
    {
        return $ltpPermit->chief_rps == $user->id && ($ltpPermit->chief_rps_signed == false || $ltpPermit->chief_rps_signed == null);
    }

    public function chiefTsdSign(User $user, LtpPermit $ltpPermit): bool
    {
        return $ltpPermit->chief_tsd == $user->id && ($ltpPermit->chief_tsd_signed == false || $ltpPermit->chief_tsd_signed == null) && $ltpPermit->chief_rps_signed == true;
    }

    public function penroSign(User $user, LtpPermit $ltpPermit): bool
    {
        return $ltpPermit->penro == $user->id && $ltpPermit->chief_tsd_signed == true && $ltpPermit->chief_rps_signed == true && ($ltpPermit->penro_signed == false || $ltpPermit->penro_signed == null);
    }
}
