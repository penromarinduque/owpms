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
        if(in_array('LTP_APPLICATION_INSPECT', $user->getUserPermissions()) && $inspectionReport->inspector_signed == null && $inspectionReport->approver_signed == null) {
            return true;
        }

        return false;
    }
}
