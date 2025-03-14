<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LtpApplication extends Model
{
    use HasFactory;

    protected $fillable = ['permittee_id', 'application_no', 'application_status', 'application_date', 'transport_date', 'purpose', 'destination', 'digital_signature'];

    protected $casts = [
        'application_date' => 'date',
        'transport_date' => 'date',
    ];

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

    public function ltpApplicationSpecies(){
        return $this->hasMany(LtpApplicationSpecie::class);
    }


    public function permittee(){
        return $this->belongsTo(Permittee::class);
    }

    public static function validateSpecies($id) {
        $statuses = LtpApplicationSpecie::where("ltp_application_id", $id)
            ->leftJoin('species', 'species.id', '=', 'ltp_application_species.specie_id')
            ->pluck('species.conservation_status')
            ->toArray();

        $has_endangered = collect($statuses)->intersect(['threatened', 'vulnerable', 'endangered'])->isNotEmpty();
        $has_no_endangered = in_array('rare', $statuses);

        if($has_endangered && $has_no_endangered) {
            return false;
        }

        return true;
    }

    public static function validateRequirements($id) {
        $attachments = LtpApplicationAttachment::where("ltp_application_id", $id)
            ->pluck("ltp_requirement_id")
            ->toArray();
    
        $requirementsExist = LtpRequirement::where("is_active_requirement", 1)
            ->whereNotIn('id', $attachments)
            ->exists(); 
    
        return !$requirementsExist;
    }
}
