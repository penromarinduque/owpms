<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;

class LtpApplication extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['permittee_id', 'application_no', 'application_status', 'application_date', 'transport_date', 'purpose', 'destination', 'digital_signature', 'year', 'no', 'io_user_id'];

    protected $casts = [
        'application_date' => 'datetime',
        'transport_date' => 'datetime',
    ];

    // 'draft','submitted','under-review','returned','resubmitted','accepted','payment-in-process','paid','for-inspection','approved'
    const STATUS_DRAFT = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_UNDER_REVIEW = 'under-review';
    const STATUS_RETURNED = 'returned';
    const STATUS_RESUBMITTED = 'resubmitted';
    const STATUS_REVIEWED = 'reviewed';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_PAYMENT_IN_PROCESS = 'payment-in-process';
    const STATUS_PAID = 'paid';
    const STATUS_FOR_INSPECTION = 'for-inspection';
    const STATUS_INSPECTION_REJECTED = 'inspection-rejected';
    const STATUS_INSPECTED = 'inspected';
    const STATUS_APPROVED = 'approved';
    const STATUS_RELEASED= 'released';
    const STATUS_REJECTED = 'rejected';
    const STATUS_EXPIRED = 'expired';

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

    public function attachments(){
        return $this->hasMany(LtpApplicationAttachment::class, 'ltp_application_id', 'id');
    }

    public function logs(){
        return $this->hasMany(LtpApplicationProgress::class, 'ltp_application_id', 'id');
    }

    public function paymentOrder(){
        return $this->hasOne(PaymentOrder::class, 'ltp_application_id', 'id');
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
    
        $requirementsExist = LtpRequirement::where([
                "is_active_requirement" => 1,
                "is_mandatory" => 1
            ])
            ->whereNotIn('id', $attachments)
            ->exists(); 
    
        return !$requirementsExist;
    }

    public function getWildlifeFarmLocation($user_id) : string {
        $user = User::find($user_id);
        $wfp = $user->wfp();
        $farm = $wfp->wildlifeFarm;
        $location = $farm->barangay->barangay_name . ', ' . $farm->barangay->municipality->municipality_name . ', ' . $farm->barangay->municipality->province->province_name;
        return $location;
    }

    public function getApplicationCountsByStatus($status, $permittee_id = null) : int {
        if($permittee_id) {
            return $this->where('application_status', $status)->where('permittee_id', $permittee_id)->count();
        }
        return $this->where('application_status', $status)->count();
    }

    public function inspectionReport() {
        return $this->hasOne(InspectionReport::class, 'ltp_application_id', 'id');
    }

    public function permit(){
        return $this->hasOne(LtpPermit::class, 'ltp_application_id', 'id');
    }
}
