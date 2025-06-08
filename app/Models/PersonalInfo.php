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

protected $fillable = ['user_id', 'last_name', 'first_name', 'middle_name', 'gender', 'email', 'contact_no', 'barangay_id'];

    public function user() {
        return $this->hasOne(User::class);
    }

    protected function barangay(){
        return $this->hasOne(Barangay::class, 'id', 'barangay_id');
    }

    
    public function getFullNameAttribute($useMiddleInitial = true){
        if($useMiddleInitial){
            return $this->first_name . ' ' . substr($this->middle_name, 0, 1) . '. ' . $this->last_name;
        }
        return $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
    }




}
