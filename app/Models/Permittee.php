<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;
use PDO;

class Permittee extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    const PERMIT_TYPE_WCP = 'wcp';
    const PERMIT_TYPE_WFP = 'wfp';

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
            ->with('wildlifeFarm')
            ->first();
    }

    public function getPermitteeWCP($user_id, $permit_type)
    {
        return $this->select('permittees.*')
            ->where('permittees.user_id', $user_id)
            ->where('permit_type', $permit_type)
            ->with('wildlifeFarm')
            ->first();
    }

    public function searchWcpPermittee($query)
    {
        return $this->select(
            'permittees.*', 
            'users.username', 
            'personal_infos.first_name', 
            'personal_infos.middle_name', 
            'personal_infos.last_name', 
            'wildlife_farms.farm_name',
            DB::raw("CONCAT(permittees.permit_number, ' - ', UPPER(personal_infos.first_name), ' ', UPPER(personal_infos.last_name)) AS text")
        )
        ->leftJoin('users', 'users.id', 'permittees.user_id')
        ->leftJoin('personal_infos', 'personal_infos.user_id', 'users.id')
        ->leftJoin('wildlife_farms', 'wildlife_farms.permittee_id', 'permittees.id')
        
        // Ensure we only select WCP permit type with status "valid"
        ->where('permittees.status', 'valid')
        ->where('permittees.permit_type', Permittee::PERMIT_TYPE_WCP)

        // Group the orWhere conditions properly
        ->where(function ($queryBuilder) use ($query) {
            $queryBuilder->where('permittees.permit_number', 'like', '%' . $query . '%')
                ->orWhere('permittees.permit_type', 'like', '%' . $query . '%')
                ->orWhere('permittees.status', 'like', '%' . $query . '%')
                ->orWhere('personal_infos.first_name', 'like', '%' . $query . '%')
                ->orWhere('personal_infos.middle_name', 'like', '%' . $query . '%')
                ->orWhere('personal_infos.last_name', 'like', '%' . $query . '%')
                ->orWhere('wildlife_farms.farm_name', 'like', '%' . $query . '%')
                ->orWhere(DB::raw("CONCAT(permittees.permit_number, ' - ', UPPER(personal_infos.first_name), ' ', UPPER(personal_infos.last_name))"), 'like', '%' . $query . '%');
        });
    }

    public function wildlifeFarm(){
        return $this->hasOne(WildlifeFarm::class, 'permittee_id', 'id');
    }

}
