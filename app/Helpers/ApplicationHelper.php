<?php

namespace App\Helpers;

use App\Models\LtpApplication;

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

    
}
