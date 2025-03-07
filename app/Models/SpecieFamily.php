<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SpecieFamily extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['family', 'is_active_family', 'specie_class_id'];

    public function specieClass(){
        return $this->belongsTo(SpecieClass::class, 'specie_class_id', 'id');
    }
}
