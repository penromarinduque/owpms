<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LtpApplicationProgress extends Model
{
    use HasFactory;

    protected $guarded = [];

    // 'submitted','under-review','returned','resubmitted','accepted','payment-in-process','paid','for-inspection'
    const STATUS_SUBMITTED = "submitted";
    const STATUS_UNDER_REVIEW = "under-review";
    const STATUS_REVIEWED = "reviewed";
    const STATUS_RETURNED = "returned";
    const STATUS_RESUBMITTED = "resubmitted";
    const STATUS_ACCEPTED = "accepted";
    const STATUS_PAYMENT_IN_PROCESS = "payment-in-process";
    const STATUS_PAID = "paid";
    const STATUS_FOR_INSPECTION = "for-inspection";

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
