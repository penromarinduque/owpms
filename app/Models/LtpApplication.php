<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LtpApplication extends Model
{
    use HasFactory;

    protected $fillable = ['permittee_id', 'application_id', 'application_status', 'application_date', 'transport_date', 'purpose', 'destination', 'digital_signature'];

    // public function getLTPApplications($user_id)
    // {
    //     return $this->select('permittee_id', 'application_id', 'application_status', 'application_date', 'transport_date', 'purpose', 'destination', 'digital_signature')
    //         ->where('')
    // }
}
