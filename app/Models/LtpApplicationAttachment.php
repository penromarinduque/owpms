<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LtpApplicationAttachment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function ltpApplication(){
        return $this->belongsTo(LtpApplication::class);
    }

    public function ltpRequirement(){
        return $this->belongsTo(LtpRequirement::class);
    }
}
