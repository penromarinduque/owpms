<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LtpFee extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function getActiveFee()
    {
        return $this->where('is_active', 1)->first(); 
    }
}
