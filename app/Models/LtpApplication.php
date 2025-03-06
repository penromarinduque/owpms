<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LtpApplication extends Model
{
    use HasFactory;

    protected $fillable = ['permittee_id', 'application_no', 'application_status', 'application_date', 'transport_date', 'purpose', 'destination', 'digital_signature'];

    // 'draft','submitted','under-review','returned','resubmitted','accepted','payment-in-process','paid','for-inspection','approved'
    const STATUS_DRAFT = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_UNDER_REVIEW = 'under-review';
    const STATUS_RETURNED = 'returned';
    const STATUS_RESUBMITTED = 'resubmitted';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_PAYMENT_IN_PROCESS = 'payment-in-process';
    const STATUS_PAID = 'paid';
    const STATUS_FOR_INSPECTION = 'for-inspection';
    const STATUS_APPROVED = 'approved';

    // public function getLTPApplications($user_id)
    // {
    //     return $this->select('permittee_id', 'application_id', 'application_status', 'application_date', 'transport_date', 'purpose', 'destination', 'digital_signature')
    //         ->where('')
    // }
}
