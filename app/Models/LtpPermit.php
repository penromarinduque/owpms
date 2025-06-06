<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LtpPermit extends Model
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
