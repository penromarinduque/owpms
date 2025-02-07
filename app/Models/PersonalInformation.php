<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PersonalInformation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['user_id', 'lastname', 'firstname', 'middlename', 'gender', 'email', 'contact_no', 'barangay_id'];
}
