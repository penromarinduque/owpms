<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class LtpRequirement extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['requirement_name', 'is_mandatory', 'is_active_requirement'];

    public function getActiveRequirements(){
        return $this->where('is_active_requirement', 1)->get();
    }

    public function checkIfMandatoryRequirementsExist($req_ids){
        return $this->where('is_mandatory', 1)
                ->where('is_active_requirement', 1)
                ->get()
                ->every(fn($req) => in_array($req->id, $req_ids));
    }
}
