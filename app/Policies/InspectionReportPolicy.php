<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InspectionReport;

class InspectionReportPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, InspectionReport $inspectionReport)
    {
        if(in_array('LTP_APPLICATION_INSPECT', $user->getUserPermissions())) {
            return true;
        }

        if($user->id == $inspectionReport->user_id) {
            return true;
        }

        return false;
    }

    public function update(User $user, InspectionReport $inspectionReport)
    {
        if(in_array('LTP_APPLICATION_INSPECT', $user->getUserPermissions()) && !$inspectionReport->inspector_signed && !$inspectionReport->approver_signed && !$inspectionReport->rps_signed) {
            return true;
        }

        return false;
    }

    public function approverSign(User $user, InspectionReport $inspectionReport)
    {
        return $inspectionReport->inspector_signed && !$inspectionReport->approver_signed && $user->id == $inspectionReport->approver_id;
    }

    public function permitteeSign(User $user, InspectionReport $inspectionReport)
    {
        return !$inspectionReport->permittee_signed && !$inspectionReport->inspector_signed && !$inspectionReport->approver_signed && $user->id == $inspectionReport->user_id;
    }

    public function inspectorSign(User $user, InspectionReport $inspectionReport)
    {
        return $inspectionReport->permittee_signed && !$inspectionReport->inspector_signed && !$inspectionReport->approver_signed && $user->id == $inspectionReport->inspector_id;
    }

    public function uploadDocument(User $user, InspectionReport $inspectionReport)
    {
        return in_array('LTP_APPLICATION_INSPECT', $user->getUserPermissions()) &&  $inspectionReport->permittee_signed && $inspectionReport->inspector_signed && $inspectionReport->approver_signed;
    }

    public function downloadDocument(User $user, InspectionReport $inspectionReport)
    {
        return (in_array('LTP_APPLICATION_INSPECT', $user->getUserPermissions()) || $user->id == $inspectionReport->user_id) &&  $inspectionReport->permittee_signed && $inspectionReport->inspector_signed && $inspectionReport->approver_signed ;
    }

}
