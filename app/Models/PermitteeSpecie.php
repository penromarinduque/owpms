<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermitteeSpecie extends Model
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function specie(){
        return $this->hasOne(Specie::class, "id", "specie_id");
    }
    
}
