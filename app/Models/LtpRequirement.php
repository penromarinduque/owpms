<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LtpRequirement extends Model
{
    use HasFactory;

    protected $fillable = ['requirement_name', 'is_mandatory', 'is_active_requirement'];
}
