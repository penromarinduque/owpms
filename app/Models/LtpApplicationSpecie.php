<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class LtpApplicationSpecie extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['ltp_application_id', 'specie_id',  'quantity', 'is_endangered'];

    public function specie()
    {
        return $this->hasOne(Specie::class, "id", "specie_id");
    }

    public function permitteeSpecies()
    {
        return $this->hasMany(PermitteeSpecie::class, "specie_id", "specie_id"); 
    }
}
