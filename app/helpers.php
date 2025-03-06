<?php

use App\Models\LtpApplication;

if(! function_exists('format_application_status')) {

    function format_application_status($status) {
        switch ($status) {
            case LtpApplication::STATUS_DRAFT:
                return 'Draft';
            case LtpApplication::STATUS_SUBMITTED:    
                return 'Submitted';
            case LtpApplication::STATUS_UNDER_REVIEW:
                return 'Under Review';
            case LtpApplication::STATUS_RETURNED:
                return 'Returned';
            case LtpApplication::STATUS_RESUBMITTED:    
                return 'Resubmitted';
            case LtpApplication::STATUS_ACCEPTED:
                return 'Accepted';
            case LtpApplication::STATUS_PAYMENT_IN_PROCESS:    
                return 'Payment in Process';
            case LtpApplication::STATUS_PAID:
                return 'Paid';
            case LtpApplication::STATUS_FOR_INSPECTION:    
                return 'For Inspection';
            case LtpApplication::STATUS_APPROVED:
                return 'Approved';
            default:
                return 'Unknown';
        }
    }

}