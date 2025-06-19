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
        return ($user->id == $ltpApplication->permittee->user_id || in_array('LTP_APPLICATION_INDEX', $user->getUserPermissions()));
    }

    /**
     * Determine whether the user can inspect the ltp application.
     */
    public function inspect(User $user, LtpApplication $ltpApplication): bool
    {
        return ($user->id == $ltpApplication->permittee->user_id || in_array('LTP_APPLICATION_INSPECT', $user->getUserPermissions()));
    }

    /**
     * Determine whether the user can upload proof of inspection of the ltp application.
     */
    public function uploadInspectionProof(User $user, LtpApplication $ltpApplication): bool
    {
        $isPermittee = $user->id == $ltpApplication->permittee->user_id;
        $hasInspectPermission = in_array('LTP_APPLICATION_INSPECT', $user->getUserPermissions());
        $isPaid = $ltpApplication->application_status == LtpApplication::STATUS_PAID;
        $isForInspection = $ltpApplication->application_status == LtpApplication::STATUS_FOR_INSPECTION;
        $isInspectionRejected = $ltpApplication->application_status == LtpApplication::STATUS_INSPECTION_REJECTED;

        if($isPaid && ($hasInspectPermission || $isPermittee)) {
            return true;
        }

        if($isInspectionRejected && $isPermittee) {
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
        return $user->id == $ltpApplication->permittee->user_id;
    }

    public function submit(User $user, LtpApplication $ltpApplication): bool
    {
        return $user->id == $ltpApplication->permittee->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LtpApplication $ltpApplication): bool
    {
        return $user->id == $ltpApplication->permittee->user_id;
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
        return ($user->id == $ltpApplication->permittee->user_id);
    }

    public function owned(User $user, LtpApplication $ltpApplication) {
        return ($user->id == $ltpApplication->permittee->user_id);
    }

    public function generateLtp(User $user, LtpApplication $ltpApplication) {
        $inspectionReport = $ltpApplication->inspectionReport;

        return $inspectionReport 
            && $ltpApplication->application_status == LtpApplication::STATUS_INSPECTED 
            && in_array('LTP_APPLICATION_INSPECT', $user->getUserPermissions())
            && $inspectionReport->permittee_signed
            && $inspectionReport->inspector_signed
            && $inspectionReport->approver_signed;
    }

    public function updateLtp(User $user, LtpApplication $ltpApplication) {
        $inspectionReport = $ltpApplication->inspectionReport;
        $ltp = $ltpApplication->permit;

        return $inspectionReport 
            && $ltpApplication->application_status == LtpApplication::STATUS_INSPECTED 
            && in_array('LTP_APPLICATION_INSPECT', $user->getUserPermissions())
            && $inspectionReport->permittee_signed
            && $inspectionReport->inspector_signed
            && $inspectionReport->approver_signed
            && (
                !$ltp->penro_signed && !$ltp->chief_tsd_signed && !$ltp->chief_rps_signed
            );
    }

    public function releaseLtp(User $user, LtpApplication $ltpApplication) {
        return in_array('LTP_APPLICATION_RELEASE', $user->getUserPermissions());
    }

    public function review(User $user, LtpApplication $ltpApplication) {
        return in_array('LTP_APPLICATION_REVIEW', $user->getUserPermissions());
    }

    public function accept(User $user, LtpApplication $ltpApplication) {
        return in_array('LTP_APPLICATION_ACCEPT', $user->getUserPermissions()) && $ltpApplication->application_status == LtpApplication::STATUS_REVIEWED;
    }

    public function return(User $user, LtpApplication $ltpApplication) {
        return in_array('LTP_APPLICATION_RETURN', $user->getUserPermissions()) && $ltpApplication->application_status == LtpApplication::STATUS_UNDER_REVIEW;
    }

    public function viewSubmittedTab(User $user) {
        $userPermissions = $user->getUserPermissions();
        return in_array('LTP_APPLICATION_REVIEW', $userPermissions)
            // || in_array('LTP_APPLICATION_ACCEPT', $userPermissions)
            || in_array('LTP_APPLICATION_RETURN', $userPermissions);
    }

    public function viewReviewedTab(User $user) {
        $userPermissions = $user->getUserPermissions();
        return in_array('LTP_APPLICATION_ACCEPT', $userPermissions);
    }

    public function uploadRequirements(User $user, LtpApplication $ltpApplication) {
        return ($user->id == $ltpApplication->permittee->user_id) && (in_array($ltpApplication->application_status, [LtpApplication::STATUS_DRAFT, LtpApplication::STATUS_RETURNED]));
    }

    public function generatePaymentOrder(User $user, LtpApplication $ltpApplication) {
        return in_array($ltpApplication->application_status, [LtpApplication::STATUS_ACCEPTED]) && in_array('PAYMENT_ORDERS_CREATE', $user->getUserPermissions());
    }
}
