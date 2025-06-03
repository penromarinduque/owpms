<?php

namespace App\Helpers;

use App\Models\LtpApplication;
use App\Models\Permittee;
use Illuminate\Support\Carbon;

class ApplicationHelper
{
    public static function formatApplicationStatus($status): string
    {
        return match ($status) {
            LtpApplication::STATUS_DRAFT => 'Draft',
            LtpApplication::STATUS_SUBMITTED => 'Submitted',
            LtpApplication::STATUS_UNDER_REVIEW => 'Under Review',
            LtpApplication::STATUS_RETURNED => 'Returned',
            LtpApplication::STATUS_RESUBMITTED => 'Resubmitted',
            LtpApplication::STATUS_ACCEPTED => 'Accepted',
            LtpApplication::STATUS_PAYMENT_IN_PROCESS => 'Payment in Process',
            LtpApplication::STATUS_PAID => 'Paid',
            LtpApplication::STATUS_FOR_INSPECTION => 'For Inspection',
            LtpApplication::STATUS_APPROVED => 'Approved',
            default => 'Unknown',
        };
    }

    public function setApplicationStatusBsColor($status): string
    {
        return match ($status) {
            LtpApplication::STATUS_DRAFT => 'secondary',
            LtpApplication::STATUS_SUBMITTED => 'primary',
            LtpApplication::STATUS_UNDER_REVIEW => 'warning',
            LtpApplication::STATUS_RETURNED => 'warning',
            LtpApplication::STATUS_RESUBMITTED => 'primary',
            LtpApplication::STATUS_ACCEPTED => 'success',
            LtpApplication::STATUS_PAYMENT_IN_PROCESS => 'secondary',
            LtpApplication::STATUS_PAID => 'success',
            LtpApplication::STATUS_FOR_INSPECTION => 'secondary',
            LtpApplication::STATUS_APPROVED => 'success',
            LtpApplication::STATUS_EXPIRED => 'secondary',
            default => 'secondary',
        };
    }

    public function setApplicationStatusBgColor($status): string
    {
        return match ($status) {
            LtpApplication::STATUS_DRAFT => 'secondary',
            LtpApplication::STATUS_SUBMITTED => 'primary',
            LtpApplication::STATUS_UNDER_REVIEW => 'warning',
            LtpApplication::STATUS_RETURNED => 'warning',
            LtpApplication::STATUS_RESUBMITTED => 'primary',
            LtpApplication::STATUS_ACCEPTED => 'success',
            LtpApplication::STATUS_PAYMENT_IN_PROCESS => 'secondary',
            LtpApplication::STATUS_PAID => 'success',
            LtpApplication::STATUS_FOR_INSPECTION => 'secondary',
            LtpApplication::STATUS_APPROVED => 'success',
            LtpApplication::STATUS_EXPIRED => 'secondary',
            default => 'secondary',
        };
    }

    public function setPermitStatusColor(Permittee $permit)
    {
        $now = Carbon::now();
        $validTo = $permit->valid_to;

        if ($now->gte($validTo)) {
            return 'permit-lv5'; // Already expired
        } elseif ($now->gte($validTo->copy()->subMonths(2))) {
            return 'permit-lv4'; // Within 2 months of expiry
        } elseif ($now->gte($validTo->copy()->subMonths(3))) {
            return 'permit-lv3'; // Within 3 months of expiry
        } elseif ($now->gte($validTo->copy()->subMonths(6))) {
            return 'permit-lv2'; // Within 6 months of expiry
        } elseif ($now->gte($validTo->copy()->subYear())) {
            return 'permit-lv1'; // Within 1 year of expiry
        }

        return null; // Or a default class
    }

    public function test(){
        return "Helper Facade working";
    }
    
}
