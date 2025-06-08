<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class WildlifeFarm extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['permittee_id', 'farm_name', 'location', 'size', 'height'];

    public function wildlifefarm()
    {
        return $this->belongsTo(Permittee::class);
    }

    public function barangay() {
        return $this->hasOne(Barangay::class, 'id', 'location');
    }

    public function getWildlifeFarm($permittee_id)
    {
        return $this->select('wildlife_farms.*')
            ->where('wildlife_farms.permittee_id', $permittee_id)
            ->first();
    }

    
}
