<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Permittee extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['user_id', 'permit_number', 'permit_type', 'valid_from', 'valid_to', 'date_of_issue', 'status'];
    
    protected $dates = ['valid_from', 'valid_to'];
    
    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function wildlifePermits()
    {
        return $this->hasMany(Permittee::class, 'user_id', 'user_id');
    }

    public function getPermittee($id)
    {
        return $this->select('permittees.*')
            ->where('permittees.id', $id)
            ->first();
    }

    public function getPermittees($user_id)
    {
        return $this->select('permittees.*')
            ->where('permittees.user_id', $user_id)
            ->get();
    }

    public function getPermitteeWFP($user_id, $permit_type)
    {
        return $this->select('permittees.*', 'wildlife_farms.farm_name', 'wildlife_farms.location', 'wildlife_farms.size', 'wildlife_farms.height')
            ->join('wildlife_farms', 'wildlife_farms.permittee_id', 'permittees.id')
            ->where('permittees.user_id', $user_id)
            ->where('permit_type', $permit_type)
            ->first();
    }

    public function getPermitteeWCP($user_id, $permit_type)
    {
        return $this->select('permittees.*')
            ->where('permittees.user_id', $user_id)
            ->where('permit_type', $permit_type)
            ->first();
    }
}
