<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LtpApplicationSpecie extends Model
{
    use HasFactory;

    protected $fillable = ['ltp_application_id', 'specie_id',  'quantity', 'is_endangered'];
}
