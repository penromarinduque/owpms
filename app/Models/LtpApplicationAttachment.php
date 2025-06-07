<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class LtpApplicationAttachment extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function ltpApplication(){
        return $this->belongsTo(LtpApplication::class);
    }

    public function ltpRequirement(){
        return $this->belongsTo(LtpRequirement::class);
    }
}
