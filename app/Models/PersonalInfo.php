<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PersonalInfo extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $primaryKey = "id"; // default it look for id

    protected $fillable = ['user_id', 'lastname', 'firstname', 'middlename', 'gender', 'email', 'contact_no', 'barangay_id'];
}
