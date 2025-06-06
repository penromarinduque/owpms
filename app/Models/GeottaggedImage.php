<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeottaggedImage extends Model
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public function ltpApplication() {
        return $this->belongsTo(LtpApplication::class);
    }
}
